<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppointmentController extends AbstractController
{
    #[Route('api/appointment', name: 'api_post_appointment', methods: ['POST'])]
    public function apiPostAppointment()
    {
        //Get form data

        //Validate form data
        //Simulate form data validation by creating fake POST and setting POST data to an array
        //Form fields are date, time, first_name, last_name, email, phoneCountryCode, phone, service_id, csrf_token
        $_POST = [
            'date' => '2021-12-31',
            'time' => '09:00',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => '',
            'phoneCountryCode' => '1',
            'phone' => '1234567890',
        ];


        //If form data is correct then make a new appointment, appointment_service and invoice. If one of the steps fails, rollback the transaction and return an error message.
    }
}