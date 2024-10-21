<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Categorie;
use App\Form\AuthorType;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorieList', name: 'app_categorie')]
    public function index(CategorieRepository $rep): Response
    {
        $categories = $rep->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/categorieListProduct/{id}', name: 'app_categorie_prod')]
    public function showProds(CategorieRepository $rep,$id): Response
    {

        $categories = $rep->find($id);
        $Prod = $categories->getProduits();
        return $this->render('categorie/showProds.html.twig', [
            'categories' => $categories,
            'Produit' => $Prod

        ]);
    }
    #[Route('/addCategorie', name: 'add_Categorie')]
    public function add(ManagerRegistry $doctrine,Request $req,CategorieRepository $catRep): Response
    {

        $categories = $catRep->findBynom($req->get('nom'));
        if($categories!=null){
            return $this->render('categorie/error.html.twig', [
               'message' => 'Cette categorie existe déjà',

            ]);
        }
        else {


            $c = new Categorie();
            $form = $this->createForm(CategorieType::class, $c);
            $form->handleRequest($req);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $doctrine->getManager();
                $em->persist($c);
                $em->flush();

                return $this->redirectToRoute('app_categorie');
            }

            return $this->render('categorie/addCat.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    }
    #[Route('/editCategorie/{id}', name: 'app_categorie_update')]
    public function edit(ManagerRegistry $doctrine,Request $req,Categorie $c): Response
    {

        $form = $this->createForm(CategorieType::class, $c);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('app_categorie');
        }

        return $this->render('categorie/addCat.html.twig', [
            'form' => $form->createView(),
        'categorie' => $c
        ]);


    }
    #[Route('/deleteCategorie/{id}', name: 'app_delete_categorie')]
    public function delete(ManagerRegistry $doctrine,Categorie $c): Response
    {
        $em = $doctrine->getManager();
        $em->remove($c);
        $em->flush();

        return $this->redirectToRoute('app_categorie');
    }



}
