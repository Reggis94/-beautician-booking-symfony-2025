<?php
namespace App\Controller;

use App\Entity\Availability;
use App\Form\Type\AvailabilityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AvailabilityController extends AbstractController{
    #[Route('/business/availability/new', name: 'new_week_availability')]
    public function newWeekAvailability(EntityManagerInterface $em, Request $request)
    {
        $availability = new Availability();
        //Create availability form
        $form = $this->createForm(AvailabilityType::class, $availability);
        dump($form);
        dump($form->isSubmitted());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //Save availability
            $em->persist($availability);
            $em->flush();
            // $this->addFlash('success', 'Availability added successfully');
            // $this->redirectToRoute('new_week_availability');
            return new Response('Availability added successfully');
        }else if($form->isSubmitted() && !$form->isValid()){
            return new Response('Availability not added');
        }
        else{
            return $this->render('availability/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
}