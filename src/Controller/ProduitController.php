<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Produit;
use App\Form\AuthorType;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    #[Route('/newProd', name: 'app_produit_new')]
    public function new(ManagerRegistry $doctrine,Request $req): Response
    { $a = new Produit();
        $form = $this->createForm(ProduitType::class, $a);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($a);
            $em->flush();

            return $this->redirectToRoute('produit_list');
        }
        return $this->render('produit/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/produitList', name: 'produit_list')]
    public function list(ManagerRegistry $doctrine,ProduitRepository $rep): Response

    {
        $produits = $rep->findAll();
        return $this->render('produit/listProd.html.twig', [
            'produits' => $produits,
        ]);
    }



    #[Route('/editProd/{id}', name: 'app_produit_edit')]
    public function edit(ManagerRegistry $doctrine, Request $req, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_list');
        }
        return $this->render('produit/new.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }
    #[Route('/delete/{id}', name: 'app_produit_delete')]
    public function delete(ManagerRegistry $doctrine, Produit $produit): Response
    {
        $em = $doctrine->getManager();
        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute('produit_list');
    }

}

