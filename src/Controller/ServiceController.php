<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\Service;
use App\Form\Type\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ServiceController extends AbstractController
{
    #[Route('/service/new', name: 'new_service')]
    public function newService(Request $request, EntityManagerInterface $em){
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            dump('VALIDE');
            $service = $form->getData();
            // $service->setBusiness($this->getUser()->getBusiness());
            //Simulation of attributing a business
            $service->setBusiness($em->getRepository(Business::class)->find(2));
            $em->persist($service);
            $em->flush();
        }
        $form = $this->createForm(ServiceType::class, $service);
        return $this->render('service/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}