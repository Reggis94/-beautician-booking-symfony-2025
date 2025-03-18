<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\Service;
use App\Form\Type\ServiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $servicesActive = $em->getRepository(Service::class)->findAllNotDeletedLastVersionByBusiness(['business' => $business]);
        return $this->render('service/list.html.twig', ['services' => $servicesActive]);
    }

    #[Route('service/edit/{id}', name:'edit_service')]
    public function editService($id, Request $request, EntityManagerInterface $em){
        $service = $em->getRepository(Service::class)->find($id);

        if(!$service){
            throw $this->createNotFoundException('Service does not exist');
        }
        //TODO: Check if service is most recent else get most recent service
        $serviceLatestVersion = $em->getRepository(Service::class)->findLatestVersion($service);
        var_dump($serviceLatestVersion->getId());
        // exit;
        //TODO: Check if current object is not deleted. Then throw NotFoundHttpException
        if(!$serviceLatestVersion || $service->getDeletedAt()){
            throw $this->createNotFoundException('Service not found');
        }
        //TODO: Check if current object has an original service. If it has one, then get most previous service.
        // $previousService = $em->getRepository(Service::class)->findOneBy(['original' => $id]);

        // $serviceOriginal = $em->getRepository(Service::class)->find($id);
        
        $serviceEdited = new Service();
        $serviceEdited->setName($serviceLatestVersion->getName());
        $serviceEdited->setDescription($serviceLatestVersion->getDescription());
        $serviceEdited->setPrice($serviceLatestVersion->getPrice());
        $serviceEdited->setDurationMinute($serviceLatestVersion->getDurationMinute());
        $serviceEdited->setBusiness($serviceLatestVersion->getBusiness());
        $serviceEdited->setCreatedAt(new \DateTimeImmutable());
        $serviceEdited->setIsActive($serviceLatestVersion->isActive());
        $serviceEdited->setOriginal($service);

        $form = $this->createForm(ServiceType::class, $serviceEdited);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $serviceEdited = $form->getData();
            // dump($serviceOriginal->getName(), $service->getName());
            // exit;
            //Check if the service has been edited
            //Dump the original service and the edited service
            dump($serviceLatestVersion, $serviceEdited);
            // exit;
            if($serviceLatestVersion->getName() != $serviceEdited->getName() || $serviceLatestVersion->getDescription() != $serviceEdited->getDescription() 
            || $serviceLatestVersion->getPrice() != $serviceEdited->getPrice() || $serviceLatestVersion->getDurationMinute() != $serviceEdited->getDurationMinute() || $serviceLatestVersion->isActive() != $serviceEdited->isActive()){
                $em->persist($serviceEdited);
                $em->flush();
            }
        }
        return $this->render('service/new.html.twig', ['form' => $form->createView()]);
    }
    
    #[Route('service/delete/{id}', name:'delete_service')]
    public function deleteService($id, Request $request, EntityManagerInterface $em){
        $service = $em->getRepository(Service::class)->find($id);
        if(!$service){
            throw $this->createNotFoundException('Service does not exist');
        }
        $serviceLatestVersion = $em->getRepository(Service::class)->findLatestVersion($service);
        if(!$serviceLatestVersion || $service->getDeletedAt()){
            throw $this->createNotFoundException('Service not found');
        }
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Delete'])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $service->setDeletedAt(new \DateTimeImmutable("now", new \DateTimeZone('UTC')));
            $em->persist($service);
            $em->flush();
        }
        return $this->render('service/delete.html.twig', ['form' => $form->createView()]);
    }
}