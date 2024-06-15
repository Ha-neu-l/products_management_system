<?php

namespace App\Controller;

use App\Entity\Rayon;
use App\Form\RayonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RayonController extends AbstractController
{
    #[Route('/rayon', name: 'app_rayon')]
    public function index(): Response
    {
        return $this->render('rayon/index.html.twig', [
            'controller_name' => 'RayonController',
        ]);
    }

    #[Route('add/rayon', name: 'rayon_add')]
    public function addR(Request $req): Response
    {
        $rayon=new Rayon();
        $form = $this->createForm(RayonType::class,$rayon);
        return $this->renderForm('rayon/create.html.twig', [
            'form' => $form,
        ]);
    }
}
