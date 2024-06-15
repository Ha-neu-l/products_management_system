<?php

namespace App\Controller;

use App\Form\MemeberType;
use App\Repository\MemeberRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Memeber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MemeberController extends AbstractController
{


    private $memberRepository;
    private $entityManager;

    public function __construct(
        MemeberRepository $memberRepository,
        ManagerRegistry $doctrine)
    {
        $this->memberRepository=$memberRepository;
        $this->entityManager=$doctrine->getManager();
    }



    #[Route('/memeber', name: 'app_memeber')]
    /**
     * @IsGranted("ROLE_ADMIN")
     **/
    public function index(): Response
    {
        $members = $this->memberRepository->findAll();
        return $this->render('memeber/index.html.twig', [
            'members' => $members,
        ]);
    }

    #[Route('add/memeber', name: 'memeber_add')]
     /**
     * @IsGranted("ROLE_ADMIN")
     **/
    public function addM(Request $req): Response
    {

        $memeber=new Memeber();
        $form = $this->createForm(MemeberType::class,$memeber);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()){
            $memeber = $form->getData();
            $this->entityManager->persist($memeber);
            $this->entityManager->flush();
            $this->addFlash('success','The new member was added successfully');
            return $this->redirectToRoute('app_memeber');
        }


        return $this->renderForm('memeber/create.html.twig', [
            'form' => $form,
        ]);
    }




    #[Route('/memeber/edit/{id}', name: 'memeber_edit')]
     /**
     * @IsGranted("ROLE_ADMIN")
     **/
    public function editMemeber(Memeber $memeber,Request $req): Response
    {
        $form = $this->createForm(MemeberType::class,$memeber);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $memeber = $form->getData();
          
            $this->entityManager->persist($memeber);
            $this->entityManager->flush();
            $this->addFlash('success','The product was updated successfully');

            return $this->redirectToRoute('app_memeber');
        }

        return $this->renderForm('memeber/edit.html.twig', [
            'form' => $form,
        ]);
    }

    
    #[Route('/memeber/delete/{id}', name: 'memeber_delete')]
     /**
     * @IsGranted("ROLE_ADMIN")
     **/
    public function delete(Memeber $memeber): Response
    {
       
        $this->entityManager->remove($memeber);
        $this->entityManager->flush();
        $this->addFlash('success','The member removed successfully');

        return $this->redirectToRoute('app_memeber');
    }


}
