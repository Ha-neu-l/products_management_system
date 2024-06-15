<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private $categoryRepository;
    private $entityManager;

    public function __construct(
        CategoryRepository $categoryRepository,
        ManagerRegistry $doctrine)
    {
        $this->categoryRepository=$categoryRepository;
        $this->entityManager=$doctrine->getManager();
    }



    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    #[Route('add/category', name: 'category_add')]
    public function addC(Request $req): Response
    {
        $category=new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success','The category was added successfully');

            return $this->redirectToRoute('app_category');
        }


        return $this->renderForm('category/create.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/category/delete/{id}', name: 'category_delete')]
    public function delete(Category $category): Response
    {
        
        $this->entityManager->remove($category);
        $this->entityManager->flush();
        $this->addFlash('success','The category removed successfully');

        return $this->redirectToRoute('app_category');
    }

}
