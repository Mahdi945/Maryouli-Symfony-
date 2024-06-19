<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;

class GestionPController extends AbstractController
{
    private $produitRepository;
    private $entityManager;

    public function __construct(ProduitRepository $produitRepository, EntityManagerInterface $entityManager)
    {
        $this->produitRepository = $produitRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/gestionP', name: 'app_gestion_produit')]
    public function index(): Response
    {
        $produits = $this->produitRepository->findAll();

        return $this->render('gestion_p/index.html.twig', [
            'controller_name' => 'GestionPController',
            'produits' => $produits,
        ]);
    }

    

    #[Route('/gestionP/delete/{id}', name: 'app_gestion_produit_delete')]
    public function delete(int $id): Response
    {
        $produit = $this->produitRepository->find($id);

        if ($produit) {
            $this->entityManager->remove($produit);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_gestion_produit');
    }
}