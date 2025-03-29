<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\Category;
use App\Form\Type\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    #[Route('business/category/show', name: 'list_category')]
    public function showCategory(EntityManagerInterface $em){
        $business = $em->getRepository(Business::class)->findOneByBusinessUser($this->getUser());
        $categories = $em->getRepository(Category::class)->findBy(['business' => $business, 'deletedAt' => null]);
        return $this->render('category/list.html.twig', ['categories' => $categories]);
    }
    //TODO: Use event dispatcher to dispatch an event when a new category service is created
    #[Route('business/category/service/new', name: 'new_category')]
    public function newCategoryService(Request $request, EntityManagerInterface $em){
        //TODO: Business in the session
        $business = $em->getRepository(Business::class)->findOneByBusinessUser($this->getUser());
        // dump($business);
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $category->setBusiness($business);
            $category->setCreatedAt(new \DateTimeImmutable(), "UTC");
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('business_dashboard');
        }
        return $this->render('category_service/new.html.twig', ['form' => $form->createView()]);
    }
}