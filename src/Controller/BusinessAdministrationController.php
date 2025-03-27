<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Business;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BusinessAdministrationController extends AbstractController{
    //TODO: Use HttpKernelInterface to make requests to the API
    #[Route('/business/dashboard', name: 'business_dashboard')]
    public function dashboard(EntityManagerInterface $em){
        // $response = $client->request(
        //     'GET',
        //     'https://api.github.com/repos/symfony/symfony-docs'
        // );
        // $response = $client->request('GET', 'http://127.0.0.1:8000/api/appointment/business/2/upcoming/');
        // if($response->getStatusCode() == 200){
        //     $upcomingAppointments = $response->toArray();
        //     dump($upcomingAppointments);
        // }
        // foreach ($client->stream($responses, 5) as $response => $chunk) {
        //     if ($chunk->isTimeout()) {
        //         // $response stale for more than 1.5 seconds

        //     }
        // }

        $business = $em->getRepository(Business::class)->findByBusinessUser($this->getUser())[0];
        // dump($business);
        // dump(gettype($this->getUser()));
        $upcomingAppointments = $em->getRepository(Appointment::class)->findUpcomingByBusiness(['business' => $business]);

        $pastAppointments = $em->getRepository(Appointment::class)->findPastByBusiness(['business' => $business]);
        return $this->render('business/dashboard.html.twig', ['upcomingAppointments' => $upcomingAppointments, 'pastAppointments' => $pastAppointments]);
    }

    #[Route('business/settings/registration', name: 'business_registration')]
    public function registrationBasedOnToken(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, Request $request){

        //TODO: Make sure that password field is not prefilled in the form
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('token', TextType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new Length(['min' => 8, 'max' => 30, 'minMessage' => 'Your password must be at least {{ limit }} characters long.', 'maxMessage' => 'Your password cannot be longer than {{ limit }} characters.']),
                    new NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class)->getForm();
            
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $user = $em->getRepository(User::class)->findOneBy(['email' => $form->get('email')->getData(), 'registrationCode' => $form->get('token')->getData(), 'validatedRegistrationCodeAt' => null]);
                if(!$user){
                    $form->addError(new FormError('Invalid email or registration code. If the problem persists, please contact the administrator.'));
                }else{
                    $password = $passwordHasher->hashPassword($user, $form->get('password')->getData());
                    $user->setPassword($password);
                    $user->setValidatedRegistrationCodeAt(new \DateTime("now", new \DateTimeZone('UTC')));
                    $em->persist($user);
                    $em->flush();

                    return $this->redirectToRoute('business_dashboard');
                }
            }
        return $this->render('business/registration.html.twig', ['form' => $form->createView()]);        
    }
}