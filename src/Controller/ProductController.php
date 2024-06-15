<?php

namespace App\Controller;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Rayon;




class ProductController extends AbstractController
{
    private $productRepository;
    private $categoryRepository;
    private $entityManager;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        ManagerRegistry $doctrine)
    {
        $this->productRepository=$productRepository;
        $this->categoryRepository=$categoryRepository;
        $this->entityManager=$doctrine->getManager();
    }


    #[Route('/product', name: 'product_list')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
            
        ]);
    }


    #[Route('/add/product', name: 'product_add')]
    public function add(Request $req): Response
    {
        $product= new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            if ($req->files->get('product')['image']) {
                $image=$req->files->get('product')['image'];
                $image_name=time().'_'.$image->getClientOriginalName();
                $image->move($this->getParameter('image_directory'),$image_name);
                $product->setImage($image_name);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $this->addFlash('success','The product was added successfully');

            return $this->redirectToRoute('product_list');
        }

        return $this->renderForm('product/create.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/product/details/{id}', name: 'product_details')]
    public function showDetails(Product $product): Response
    {
        return $this->render('product/productDetail.html.twig', [
            'product' => $product,
            'photo_url'=>'http://127.0.0.1:8000/uploads/'
        ]);
    }

    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function editProduct(Product $product,Request $req): Response
    {
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            if ($req->files->get('product')['image']) {
                $image=$req->files->get('product')['image'];
                $image_name=time().'_'.$image->getClientOriginalName();
                $image->move($this->getParameter('image_directory'),$image_name);
                $product->setImage($image_name);
            }
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $this->addFlash('success','The product was updated successfully');

            return $this->redirectToRoute('product_list');
        }

        return $this->renderForm('product/edit.html.twig', [
            'form' => $form,
        ]);
    }

    
    #[Route('/product/delete/{id}', name: 'product_delete')]
    public function delete(Product $product): Response
    {
        $filesystem = new Filesystem();
        $imagePath = './uploads/'.$product->getImage();
        $filesystem->remove($imagePath);
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        $this->addFlash('success','The product removed successfully');

        return $this->redirectToRoute('product_list');
    }

    #[Route('/product/rayon/{idRayon}', name: 'rayon_product_list')]
    public function rayonProduct(int $idRayon): Response
    {
       
        $products = $this->productRepository->findProductsOfRayon($idRayon);
        return $this->render('product/rayonProducts.html.twig', [
            'products' => $products,     
        ]);
    }


}
