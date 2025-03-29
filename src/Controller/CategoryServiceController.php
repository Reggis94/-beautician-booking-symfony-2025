<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\Type\CategoryServiceType;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CategoryServiceController extends AbstractController
{
    //TODO: Use event dispatcher to dispatch an event when a new category service is created
    // #[Route('business/category/service/new', name: 'new_category_service')]
    public function newCategoryService(Request $request, EntityManagerInterface $em){
        $form = $this->createForm(CategoryServiceType::class);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            $categoryService = $form->getData();
            $categoryService->setCreatedAt(new \DateTimeImmutable(), "UTC");
            $em->persist($categoryService);
            $em->flush();
            return $this->redirectToRoute('business_dashboard');
        }
        return $this->render('category_service/new.html.twig', ['form' => $form->createView()]);
    }
}