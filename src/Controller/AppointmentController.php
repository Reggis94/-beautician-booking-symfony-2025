<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\Type\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends AbstractController
{
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

    #[Route('busineess/appointment/{id}/new', name: 'business_new_appointment')]
    public function businessNew(){
        return new Response('New appointment');
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
}