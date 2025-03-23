<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Business;
use App\Entity\AppointmentService;
use App\Entity\Service;
use App\Form\Type\AppointmentType;
use App\Service\AppointmentOverlapChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;

class AppointmentController extends AbstractController
{
    #[Route('business/appointment/show', name: 'business_all_appointment')]
    public function appointmentAll(EntityManagerInterface $em): Response
    {
        //TODO: Add voter to check if the user is the owner of the appointment
        $business = $em->getRepository(Business::class)->find(2);
        //TODO: Get latest version of each appointment where deletedAt is null
        $appointment = $em->getRepository(Appointment::class)->findBy(['business' => $business]);

        if (!$appointment) {
            throw $this->createNotFoundException('No appointment found for id ' . $id);
        }

        return $this->render('appointment/list.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('business/appointment/{id}/show', name: 'business_show_appointment')]
    public function appointmentShow(int $id, EntityManagerInterface $em): Response
    {
        //TODO: Add voter to check if the user is the owner of the appointment
        $appointment = $em->getRepository(Appointment::class)->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('No appointment found for id ' . $id);
        }

        return $this->render('appointment/business_show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('business/appointment/new', name: 'business_new_appointment')]
    public function businessNew(Request $request, EntityManagerInterface $em, AppointmentOverlapChecker $appointmentOverlapChecker): Response{
        //TODO: Get current business
        //$business = $this->getUser()->getBusiness();
        $business = $em->getRepository(Business::class)->find(2);
        $form = $this->createForm(AppointmentType::class);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            $appointment = $form->getData();
            $appointment->setCreatedAt(new \DateTimeImmutable(), "UTC");
            $appointment->setTimezone($business->getTimezoneName());
            $appointment->setBusiness($business);
            // var_dump($form->getData());exit;
            $appointmentServicesSelected = $form->get('appointmentServices')->getData();
            // dump($form->get('appointmentServices')->getData()[0]->getId());
            $appointmentOverlap = $appointmentOverlapChecker->checkOverlap($appointment, $business, $appointmentServicesSelected);
            if($appointmentOverlap){
                //TODO: Set an error for the form
                $form->addError(new FormError('The appointment overlaps with another appointment'));
                return $this->render('appointment/new.html.twig', ['form' => $form->createView(), 'appointmentOverlap' => $appointmentOverlap]);
            }

            exit;
            $appointmentService = new AppointmentService();
            foreach($appointmentServicesSelected as $s){
                $appointmentService->setAppointment($appointment);
                $appointmentService->setService($s);
                $em->persist($appointmentService);
            }
            $em->persist($appointment);
            $em->flush();
            // exit;
            return $this->redirectToRoute('business_dashboard');
        }
        return $this->render('appointment/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/business/appointment/{id}/edit', name: 'business_edit_appointment')]
    public function businessEdit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $appointment = $em->getRepository(Appointment::class)->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('No appointment found for id ' . $id);
        }

        $appointmentForm = $this->createForm(AppointmentType::class, $appointment);

        $appointmentForm->handleRequest($request);
        if ($appointmentForm->isSubmitted() && $appointmentForm->isValid()) {
            $appointment = $appointmentForm->getData();
            // $appointment->setUpdatedAt(new \DateTimeImmutable(), "UTC");
            $appointmentServicesSelected = $appointmentForm->get('appointmentServices')->getData();

            foreach($appointment->getAppointmentServices() as $as){
                $em->remove($as);
            }

            foreach($appointmentServicesSelected as $s){
                $appointmentService = new AppointmentService();
                $appointmentService->setAppointment($appointment);
                $appointmentService->setService($s);
                $em->persist($appointmentService);
            }
            //Call API
            dump('VALIDE');
            $em->flush();
            // exit;
            return $this->redirectToRoute('appointment_success');
        }
        

        return $this->render('appointment/edit_by_business.html.twig', [
            'form' => $appointmentForm->createView(),
        ]);
    }

    #[Route('/business/appointment/{id}/delete', name: 'business_delete_appointment')]
    public function businessDelete(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $appointment = $em->getRepository(Appointment::class)->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('No appointment found for id ' . $id);
        }

        $this->createFormBuilder()
            ->setAction($this->generateUrl('business_delete_appointment', ['id' => $id]))
            ->setMethod('DELETE')
            ->getForm()
            ->handleRequest($request);

            if($form->isSubmitted && $form->isValid()){
                $appointment->setDeletedAt(new \DateTimeImmutable(), "UTC");
                $em->flush();
            }

        return $this->redirectToRoute('business_all_appointment');
    }

    #[Route('/business/appointment/{id}/cancel', name: 'business_cancel_appointment')]
    public function businessCancel(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $appointment = $em->getRepository(Appointment::class)->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('No appointment found for id ' . $id);
        }

        $this->createFormBuilder()
            ->setAction($this->generateUrl('business_cancel_appointment', ['id' => $id]))
            ->setMethod('POST')
            ->getForm()
            ->handleRequest($request);

        if($form->isSubmitted && $form->isValid()){
            $appointment->setCanceledAt(new \DateTimeImmutable(), "UTC");
            $em->flush();
        }

        return $this->redirectToRoute('business_all_appointment');
    }
    //TODO: Make that route as API for later
    #[Route('business/check-overlapping-appointment/{startDate}/{startTime}/{endDate}/{endTime}', name: 'business_check_overlapping_appointment', requirements: [
        'startDate' => '\d{4}-\d{2}-\d{2}',
        'startTime' => '\d{2}:\d{2}',
        'endDate' => '\d{4}-\d{2}-\d{2}',
        'endTime' => '\d{2}:\d{2}'
    ])]
    public function checkOverlappingAppointment(string $startDate, string $startTime, string $endDate, string $endTime, EntityManagerInterface $em): Response
    {
        //TODO: Check current session else return 401
        $business = $em->getRepository(Business::class)->find(2);

        // dump($startDate, $startTime, $endDate, $endTime);
        $startNewAppointmentStringDateTime = $startDate . ' ' . $startTime;
        $endNewAppointmentStringDateTime = $endDate . ' ' . $endTime;
        // dump($startNewAppointmentStringDateTime, $endNewAppointmentStringDateTime);

        //Convert from business timezone to UTC

        $startNewAppointmentStringDateTimeBusinessTimezone = new \DateTime($startNewAppointmentStringDateTime, new \DateTimeZone($business->getTimezoneName()));
        $startNewAppointmentStringDateTimeUtc = $startNewAppointmentStringDateTimeBusinessTimezone->setTimezone(new \DateTimeZone('UTC'));
        $endNewAppointmentStringDateTimeBusinessTimezone = new \DateTime($endNewAppointmentStringDateTime, new \DateTimeZone($business->getTimezoneName()));
        $endNewAppointmentStringDateTimeUtc = $endNewAppointmentStringDateTimeBusinessTimezone->setTimezone(new \DateTimeZone('UTC'));
        // dump($startNewAppointmentStringDateTimeBusinessTimezone->format('Y-m-d H:i:s'), $endNewAppointmentStringDateTimeUtc->format('Y-m-d H:i:s'));
        
        $appointments = $em->getRepository(Appointment::class)->findOverlapping(['business' => $business, 'startDateTimeUtc' => $startNewAppointmentStringDateTimeUtc->format('Y-m-d H:i:s'), 'endDateTimeUtc' => $endNewAppointmentStringDateTimeUtc->format('Y-m-d H:i:s')]);
        // dump(array_column($appointments, 'id'));
        // exit;

        //TODO: In case it is an entity
        // $appointments = array_map(function($appointment){
        //     return [
        //         'id' => $appointment->getId(),
        //         'lastName' => $appointment->getLastName(),
        //         'startDateTimeUtc' => $appointment->getStartDateTimeUtc(),
        //         'startDateTimeConvertedToTimeZone' => $appointment->getStartDateTimeConvertedToTimeZone(),
        //         'endDateTimeUtc' => $appointment->getEndDateTimeUtc(),
        //         'endDateTimeConvertedToTimeZone' => $appointment->getEndDateTimeConvertedToTimeZone()
        //     ];
        // }, $appointments);

        //For now returning query columns
        $appointments = array_map(function($appointment){
            return [
                'id' => $appointment['id'],
                'lastName' => $appointment['last_name'],
                'startDateTimeUtc' => $appointment['start_date_time_utc'],
                'endDateTimeUtc' => $appointment['end_date_time_utc']
            ];
        }, $appointments);

        //TODO: Use serializer group
        return $this->json(['appointments' => $appointments], 200);
    }
}