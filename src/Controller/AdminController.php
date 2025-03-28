<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Business;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    #[Route('/admin/user/new', name: 'adminUserNew')]
    public function newAccount(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('businessName', TextType::class)
            ->add('businessTimezone', TimezoneType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //TODO: Check email and username uniqueness
            //TODO: Deal with redundant Username in USER and BUSINESS tables
            $data = $form->getData();
            $user = new User();
            $business = new Business();
            
            $user->setEmail($data['email']);
            $user->setUsername($data['username']);
            $user->setCreatedAt(new \DateTime("now", new \DateTimeZone("UTC")));
            $business->setName($data['businessName']);
            $business->setTimezoneName($data['businessTimezone']);
            $business->setBusinessUser($user);
            $business->setUsername($data['username']);
            $business->setCreatedAt(new \DateTimeImmutable("now", new \DateTimeZone("UTC")));

            $registrationCode = bin2hex(random_bytes(5));
            $user->setRegistrationCode($registrationCode);

            $em->persist($user);
            $em->persist($business);
            $em->flush();
        }
        return $this->render('admin/new-user.html.twig', [
            'form' => $form->createView()
        ]);
    }
}