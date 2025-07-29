<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {

        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_tweet_index');
        }


        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
