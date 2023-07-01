<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminutilityController extends AbstractController
{
    #[Route('/adminutility', name: 'app_adminutility')]
    public function index(UserRepository $repo): Response
    {
        // return $this->render('adminutility/index.html.twig', [
        //     'controller_name' => 'AdminutilityController',
        // ]);
        $users = $repo->findBy(
            [],
            ['email' => 'DESC'],
            5,
        );
        return $this->json([
            'users' => $users
        ]);
    }
}
