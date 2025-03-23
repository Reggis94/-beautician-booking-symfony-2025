<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\Business;
use App\Entity\AppointmentService;
use App\Entity\Service;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class AppointmentOverlapChecker
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Check if the appointment overlaps with any other appointments
     * Returns an array of overlapping appointments
     *
     * @param Appointment $appointment
     * @param Business $business
     * @param array $appointmentServices the collection of services for the appointment of type Service[]
     * @return array
     */
    public function checkOverlap(Appointment $appointment, Business $business, array|ArrayCollection $appointmentServices = []): array
    {
        //Get all services duration
        if($appointmentServices instanceof ArrayCollection){
            $appointmentServices = $appointmentServices->toArray();
        }

        $servicesDuration = array_reduce($appointmentServices, function ($carry, Service $appointmentService) {
            return $carry + $appointmentService->getDurationMinute();
        }, 0);

        $endDateTimeUtc = $appointment->getStartDateTimeUtc()->modify('+' . $servicesDuration . ' minutes');

        // dump($appointment->getStartDateTimeUtc()->format('Y-m-d H:i'), $endDateTimeUtc->format('Y-m-d H:i'));
        // exit;


        return $this->entityManager->getRepository(Appointment::class)->findOverlapping([
            'business' => $business,
            'startDateTimeUtc' => $appointment->getStartDateTimeUtc()->format('Y-m-d H:i:s'),
            'endDateTimeUtc' => $endDateTimeUtc->format('Y-m-d H:i:s'),
        ]);
    }
}