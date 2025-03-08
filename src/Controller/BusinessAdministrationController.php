<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Business;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class BusinessAdministrationController extends AbstractController{
    //TODO: Use HttpKernelInterface to make requests to the API
    #[Route('/business/dashboard', name: 'business_dashboard')]
    public function dashboard(EntityManagerInterface $em){
        // $response = $client->request(
        //     'GET',
        //     'https://api.github.com/repos/symfony/symfony-docs'
        // );
        // $response = $client->request('GET', 'http://127.0.0.1:8000/api/appointment/business/2/upcoming/');
        // if($response->getStatusCode() == 200){
        //     $upcomingAppointments = $response->toArray();
        //     dump($upcomingAppointments);
        // }
        // foreach ($client->stream($responses, 5) as $response => $chunk) {
        //     if ($chunk->isTimeout()) {
        //         // $response stale for more than 1.5 seconds

        //     }
        // }

        //TODO: Use the user to get the business id
        // $em->getRepository(Appointment::class)->findUpcomingByBusiness(['business' => $this->getUser()]);
        $upcomingAppointmentsFetchAssoc = $em->getRepository(Appointment::class)->findUpcomingByBusiness(['business' => $em->getRepository(Business::class)->find(2)]);
        dump($upcomingAppointmentsFetchAssoc);
        $upcomingAppointments = [];
        foreach($upcomingAppointmentsFetchAssoc as $appointment){
            $a = $em->getRepository(Appointment::class)->find($appointment['appointment_id']);
            $upcomingAppointments[] = $a;
        }
        return $this->render('business/dashboard.html.twig', ['upcomingAppointments' => $upcomingAppointments]);
    }
}