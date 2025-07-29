<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\Like;
use App\Entity\Commentaire;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tweet')]
final class TweetController extends AbstractController
{

    // POSTER NOUVEAU TWEET
    #[Route(name: 'app_tweet_index', methods: ['GET', 'POST'])]
    public function index(Request $request, TweetRepository $tweetRepository, EntityManagerInterface $entityManager): Response
    {
        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $tweet->setDateTweet(new \DateTime());
            $tweet->setUser($this->getUser());

            if ($this->getUser()) {
                $tweet->setUser($this->getUser());
            }

            $entityManager->persist($tweet);
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index');
        }

        return $this->render('tweet/index.html.twig', [
            'tweets' => $tweetRepository->findAllOrderedByIdDesc(),
            'form' => $form->createView(),
        ]);
    }

    // COMMENTER UN TWEET

    #[Route('/{id}/commentaire', name: 'app_tweet_commentaire', methods: ['POST'])]
    public function commentaire(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $content = $request->request->get('content');

        $commentaire = new Commentaire();
        $commentaire->setUser($user);
        $commentaire->setTweet($tweet);
        $commentaire->setDateComment(new \DateTime());
        $commentaire->setContent($content);

        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_tweet_index');
    }


    // AFFICHE LES COMMENTAIRES D'UN TWEET
    #[Route('/{id}/commentaires', name: 'app_commentaire_show', methods: ['GET'])]
    public function showComment(Tweet $tweet): Response
    {
        $commentaires = $tweet->getCommentaires();

        return $this->render('commentaire/show.html.twig', [
            'tweet' => $tweet,
            'commentaires' => $commentaires,
        ]);
    }


    // LIKE UN TWEET

    #[Route('/{id}/like', name: 'app_tweet_like', methods: ['POST'])]
    public function like(Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $existingLike = $entityManager->getRepository(Like::class)->findOneBy([
            'tweet' => $tweet,
            'user' => $user,
        ]);

        if ($existingLike) {

            $entityManager->remove($existingLike);
        } else {

            $like = new Like();
            $like->setTweet($tweet);
            $like->setUser($user);

            $entityManager->persist($like);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_tweet_index');
    }

    // CREER UN TWEET
    #[Route('/new', name: 'app_tweet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweet->setUser($this->getUser());
            $entityManager->persist($tweet);
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/new.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }

    // AFFICHER DETAIL D'UN TWEET
    #[Route('/{id}', name: 'app_tweet_show', methods: ['GET'])]
    public function show(Tweet $tweet): Response
    {
        return $this->render('tweet/show.html.twig', [
            'tweet' => $tweet,
        ]);
    }


    // EDITER UN TWEET 
    #[Route('/{id}/edit', name: 'app_tweet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/edit.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }


    // SUPPRIMER UN TWEET
    #[Route('/{id}', name: 'app_tweet_delete', methods: ['POST'])]
    public function delete(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tweet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tweet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }
}
