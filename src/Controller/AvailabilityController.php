<?php
namespace App\Controller;

use App\Entity\Availability;
use App\Entity\Business;
use App\Entity\Day;
use App\Form\Type\WeekAvailabilityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AvailabilityController extends AbstractController{
    #[Route('/business/availability/new', name: 'new_week_availability')]
    public function newWeekAvailability(EntityManagerInterface $em, Request $request)
    {
        //TODO: This page is only visible if there is no week days open in database
        //TODO: If there is already week days open in database, redirect to edit page
        //TODO: We shall add a weekAvailabilityVersion of 0 field in the business table to keep track of the week availability version
        //TODO: We shall increment weekAvailabilityVersion by 1 each time a week availability is edited
        $daysString = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        //Create availability form
        $form = $this->createForm(WeekAvailabilityType::class);
        $form->handleRequest($request);
        dump($form->getData());
        dump($request->isMethod('POST'));
        if($form->isSubmitted() && $form->isValid()){
            dump($form->getData());

            foreach($daysString as $dayString){
                foreach($form->getData() as $key => $value){
                    if($key == 'startTime' . $dayString){
                        $availability = new Availability();
                        $dayObj = $em->getRepository(Day::class)->findOneBy(['name' => $dayString]);
                        $availability->setDay($dayObj);
                        $availability->setStartTime($value);
                        $availability->setEndTime($form->getData()['endTime' . $dayString]);
                        $availability->setIntervalMinutes($form->getData()['intervalMinutes' . $dayString]);
                        $availability->setBusiness($em->getRepository(Business::class)->find(2));
                        //$availability->setBusiness($this->getUser()->getBusiness());
                        $em->persist($availability);
                    }
                }
            }
                    

            //Save availability
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