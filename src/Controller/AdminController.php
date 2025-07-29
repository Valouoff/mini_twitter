<?php

namespace App\Controller;

// use DateTime;

use App\Entity\User;
use App\Entity\Tweet;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TweetRepository;
use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



#[Route('/admin')]
final class AdminController extends AbstractController
{

    #[Route(name: 'app_admin_index')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // AFFICHER //

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(User $user, TweetRepository $tweetRepository, LikeRepository $likeRepository): Response
    {
        $tweets = $tweetRepository->findBy(['user' => $user]);
        $likes = $likeRepository->findBy(['user' => $user]);

        return $this->render('admin/show.html.twig', [
            'user' => $user,
            'tweets' => $tweets,
            'likes' => $likes
        ]);
    }

    // SUPPRIMER //

    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
