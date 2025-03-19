<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Business;
use App\Entity\AppointmentService;
use App\Entity\Service;
use App\Form\Type\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function businessNew(Request $request, EntityManagerInterface $em){
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
            // var_dump($form->get('appointmentServices')->getData());exit;
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
}