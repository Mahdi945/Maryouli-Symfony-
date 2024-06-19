<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class GestionUController extends AbstractController
{
    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/gestionU', name: 'app_gestion_utilisateurs')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('gestion_u/index.html.twig', [
            'controller_name' => 'GestionUController',
            'users' => $users,
        ]);
    }

    #[Route('/gestionU/delete/{id}', name: 'app_gestion_utilisateurs_delete')]
    public function delete(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_gestion_utilisateurs');
    }
}
