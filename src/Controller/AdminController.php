<?php

namespace App\Controller;

use DateTime;
use App\Repository\UserRepository;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{

    // #[Route('/user', name: 'app_user_login')]
    // public function index(ManagerRegistry $registry): Response
    // {
    //     return $this->render('admin/index.html.twig', [
    //         'users' => $registry->findAll(),
    //     ]);
    }
    #[Route('/admin', name: 'app_admin')]
    public function index(TweetRepository $tweetRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Dashboard Admin',
            'date' => new DateTime()
            'user_username' => 'Pseudo',
            'user' => $categoryRepository->findAll(),
        ]);
    }
}
