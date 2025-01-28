<?php

namespace App\Controller\API;

use App\Entity\Appointment;
use App\Entity\Business;
use App\Entity\Invoice;
use App\Entity\Service;
use App\Entity\AppointmentService;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    //All GET requests
    #[Route('api/appointment/business/{businessId}/upcoming/', name: 'api_get_appointment_business_upcoming', methods: ['GET'])]
    public function apiGetAppointmentBusinessUpcoming(int $businessId, EntityManagerInterface $entityManager): JsonResponse
    {
        //Get business by ID
        $business = $entityManager->getRepository(Business::class)->find($businessId);
        
        //Get all upcoming appointments of a business
        $appointments = $entityManager->getRepository(Appointment::class)->findUpcomingByBusiness(['business' => $business]);
        // dump($appointments);
        //Return the appointments in JSON format
        return new JsonResponse($appointments);
    }

    //Past appointments
    //Work on cache if the query is long when serializer groups will be used
    #[Route('api/appointment/business/{businessId}/past/', name: 'api_get_appointment_business_past', methods: ['GET'])]
    public function apiGetAppointmentBusinessPast(int $businessId, EntityManagerInterface $entityManager){
        $business = $entityManager->getRepository(Business::class)->find($businessId);
        $appointments = $entityManager->getRepository(Appointment::class)->findPastByBusiness(['business' => $business]);
        return new JsonResponse($appointments);
    }

    //All POST requests
    #[Route('api/appointment', name: 'api_post_appointment', methods: ['POST'])]
    public function apiPostAppointment(EntityManagerInterface $entityManager): JsonResponse
    {
        //Get form data

        //Validate form data
        //Check if price is the same as in the database (price + commission fee 10%)
        //If price is not the same, return an error message else get price from database
        $price = 100.0;

        //Simulate form data validation by creating fake POST and setting POST data to an array
        //Form fields are date, time, first_name, last_name, email, phoneCountryCode, phone, service_id, csrf_token
        // $_POST = [
        //     'date' => '2025-12-31',
        //     'time' => '09:00',
        //     'first_name' => 'John',
        //     'last_name' => 'Doe',
        //     'email' => '',
        //     'phone_country_code' => '1',
        //     'phone' => '1234567890',
        //     'services' => [1]
        // ];

        //Get timezone of business from database
        // $businessId = 1;
        // $business = $entityManager->getRepository(Business::class)->find($businessId);
        // $businessTimezone = $business->getTimezone();

        //Simulate business timezone
        $businessTimezone = 'America/New_York';

        // dump($_POST);
        //Convert to UTC time
        $dateTime = new \DateTime($_POST['date'] . ' ' . $_POST['time'], new \DateTimeZone($businessTimezone));
        $dateTimeUTC = $dateTime->setTimezone(new \DateTimeZone('UTC'));

        $appointment = new Appointment();
        $appointment->setStartDateTimeUtc(new \DateTimeImmutable($dateTimeUTC->format('Y-m-d H:i:s'), new \DateTimeZone('UTC')));
        $appointment->setFirstName($_POST['first_name']);
        $appointment->setLastName($_POST['last_name']);
        $appointment->setEmail($_POST['email']);
        $appointment->setPhoneCountryCode($_POST['phone_country_code']);
        $appointment->setPhoneNumber($_POST['phone']);
        $appointment->setTimezone($businessTimezone);
        //Get the final price
        
        $invoice = new Invoice();
        $bookingFees = $price * 0.1; 
        $invoice->setClientBookingFees($bookingFees);
        $invoice->setPrice($price + $bookingFees);
        $invoice->setAppointment($appointment);
        $invoice->setPaymentProcessingFees(0.0);

        //Get all services of the appointment
        $services = [];
        $servicesIds = $_POST['services'];

        $appointmentServices = [];
        
        foreach($servicesIds as $serviceId) {
            $service = $entityManager->getRepository(Service::class)->find($serviceId);
            $services[] = $service;
            $appointmentService = new AppointmentService();
            $appointmentService->setService($service);
            $appointmentService->setAppointment($appointment);
            $appointmentService->setPrice($service->getPrice());
            $appointmentServices[] = $appointmentService;
        }

        //Insert the appointment, invoice and appointment_services in the database
        //Rollback the transaction if one of the steps fails

        $entityManager->getConnection()->beginTransaction(); // Start transaction

        try {
            // Persist appointment
            $entityManager->persist($appointment);

            // Persist invoice
            $entityManager->persist($invoice);

            // Persist appointment services
            foreach ($appointmentServices as $appointmentService) {
                $entityManager->persist($appointmentService);
            }

            $entityManager->flush();
            $entityManager->getConnection()->commit(); // Commit transaction

            return new JsonResponse(['status' => 'success', 'message' => 'Appointment created successfully.']);
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollBack(); // Rollback transaction
            $entityManager->close(); // Close entity manager
            return new JsonResponse(['status' => 'error', 'message' => 'Failed to create appointment.'], 500);
        }
    }
}