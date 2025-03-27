<?php

namespace App\Controller\API;

use App\Entity\Business;
use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class ServiceController extends AbstractController
{
    #[Route('/api/business/{businessId}/service', name: 'api_business_service', methods: ['GET'])]
    public function getServiceAllByBusinessId(EntityManagerInterface $entityManager, SerializerInterface $serializer, $businessId): JsonResponse
    {
        // Get all the available services

        $business = $entityManager->getRepository(Business::class)->find($businessId);
        $services = $entityManager->getRepository(Service::class)->findBy(['business' => $business]);

        $data = [
            'businessId' => $businessId,
            'business' => $business,
            'services' => $services
        ];

        return $this->json($services, 200, [], ['groups' => 'service.client']);
    }
}