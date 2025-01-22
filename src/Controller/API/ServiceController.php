<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServiceController extends AbstractController
{
    /**
     * @Route("/client/service/{businessId}", name="client_service", methods={"GET"})
     */
    public function getServiceAllByBusinessId($businessId): JsonResponse
    {
        // Your logic here
        $data = [
            'businessId' => $businessId,
            'service' => 'Example Service'
        ];

        return new JsonResponse($data);
    }
}