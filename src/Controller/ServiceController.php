<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\Type\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/new', name: 'new_service')]
    public function newService(){
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        return $this->render('service/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}