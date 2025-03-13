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
            $service->setCreatedAt(new \DateTimeImmutable());
            $em->persist($service);
            $em->flush();
        }
        $form = $this->createForm(ServiceType::class, $service);
        return $this->render('service/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('service/show', name:'list_service')]
    public function listService(EntityManagerInterface $em){
        //TODO get business by current user
        //Get business
        $business = $em->getRepository(Business::class)->find(2);
        $services = $em->getRepository(Service::class)->findAllByBusiness(['business' => $business]);
        return $this->render('service/list.html.twig', ['services' => $services]);
    }

    #[Route('service/edit/{id}', name:'edit_service')]
    public function editService($id, EntityManagerInterface $em){
        $service = $em->getRepository(Service::class)->find($id);
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $serviceSoftUpdate = $form->getData();
            if($serviceSoftUpdate != $service){
                $serviceSoftUpdate->setCreatedAt(new \DateTimeImmutable());
                $em->persist($service);
                $em->flush();
            }

        }
    }
}