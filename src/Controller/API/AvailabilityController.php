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
        $availabilities = $entityManager->getRepository(Availability::class)->findByBusiness($business);
        //TODO: If there are no availabilities, we return an empty array

        //Once the availabilities are fetched, we create an array for each day that will contain time slots by intervals
        //Get today's day of the week
        $today = date('N');

        return $this->json($today);
    }
}