<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class ProduitController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    #[Route('/admin', name: 'app_produit')]
   

    public function index(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            //****************Manage Uploaded FileName
            $photo_prod = $form->get('photo')->getData();
            $originalFilename = $photo_prod->getClientOriginalName();
            $newFilename = $originalFilename.'-'.uniqid().'.'.$photo_prod->getClientOriginalExtension();
            
            $photo_prod->move($this->getParameter('images_directory'),$newFilename);
            $produit->setPhoto($newFilename);
            //****************
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
            //return $this->redirectToRoute('confirm');
        }
        return $this->render('produit/index.html.twig', ['form' => $form->createView(),]);
    }
}
