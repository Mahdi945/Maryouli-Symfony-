<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;

class AfficheProduitController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/affiche/produit', name: 'app_affiche_produit')]
    public function index(Request $request): Response
    {
        $search = $request->query->get('search');
        $taille = $request->query->get('taille');

        $repository = $this->doctrine->getRepository(Produit::class);
        $queryBuilder = $repository->createQueryBuilder('p');

        if ($search) {
            $queryBuilder->andWhere('p.nom LIKE :search')
                         ->setParameter('search', '%' . $search . '%');
        }

        if ($taille) {
            $queryBuilder->andWhere('p.taille = :taille')
                         ->setParameter('taille', $taille);
        }

        $query = $queryBuilder->getQuery();
        $articles = $query->getResult();

        return $this->render('affiche_produit/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}

