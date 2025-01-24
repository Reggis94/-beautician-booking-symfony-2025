<?php
namespace App\Controller\API;

use App\Entity\Availability;
use App\Entity\Business;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AvailabilityController extends AbstractController
{
    #[Route('api/availability/{businessId}/{weekStartDate}', name: 'api_business_availability', methods: ['GET'] )]
    public function getAvailabilityByBusiness(EntityManagerInterface $entityManager, $businessId, $weekStartDate): JsonResponse {
        $business = $entityManager->getRepository(Business::class)->find($businessId);
        $availabilitiesArray = [];
        //Associate the name of the days in $targetDays to the next 7 days starting from the weekStartDate
        //Get the day of the week of the weekStartDate
        //For loop from 0 to 6
        for($i = 0; $i < 7; $i++) {
            //0 == weekStartDate
            //Get the name of the day of the weekStartDate
            if($i == 0){
                $day = date('l', strtotime($weekStartDate));
                $dateValue = date('Y-m-d', strtotime($weekStartDate));
                $dateLabel = date('l, d \of F Y', strtotime($weekStartDate));
            } else {
                $day = date('l', strtotime($weekStartDate . ' + ' . $i . ' days'));
                $dateValue = date('Y-m-d', strtotime($weekStartDate . ' + ' . $i . ' days'));
                $dateLabel = date('l, d \of F Y', strtotime($weekStartDate . ' + ' . $i . ' days'));
            }

            $availabilitiesOfTheDay = $entityManager->getRepository(Availability::class)->findAvailabilityByDayNameAndBusinessId($day, $businessId);
            
            if (!empty($availabilitiesOfTheDay)) {
                if(!array_key_exists($dateValue, $availabilitiesArray)){
                    if($availabilitiesOfTheDay[0]['interval_minutes'] == null || $availabilitiesOfTheDay[0]['interval_minutes'] == 0)
                        $availabilitiesOfTheDay[0]['interval_minutes'] = 5;
                    $availabilitiesArray[$dateValue] = [
                        'dateLabel' => $dateLabel,
                        'intervalMinutes' => $availabilitiesOfTheDay[0]['interval_minutes'],
                        'timeSlots' => [],
                        'startTime' => $availabilitiesOfTheDay[0]['start_time'],
                        'endTime' => $availabilitiesOfTheDay[0]['end_time']
                    ];
                }
            }
        }   
        return $this->json($availabilitiesArray);
    }
}