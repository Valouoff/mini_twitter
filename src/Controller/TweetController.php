<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\Like;
use App\Entity\Commentaire;
use App\Entity\Retweet;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/tweet')]
#[IsGranted('ROLE_USER')]
final class TweetController extends AbstractController
{

    // POSTER NOUVEAU TWEET
    #[Route(name: 'app_tweet_index', methods: ['GET', 'POST'])]
    public function index(Request $request, TweetRepository $tweetRepository, EntityManagerInterface $entityManager): Response
    {
        $tweet = new Tweet();
        $onglet = $request->query->get('onglet', 'for_you');
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweet->setUser($this->getUser());

            $mediaFile = $form->get('imageFile')->getData();

            if ($mediaFile) {
                $newFilename = uniqid() . '.' . ($mediaFile->guessExtension()
                    ?: strtolower(pathinfo($mediaFile->getClientOriginalName(), PATHINFO_EXTENSION) ?: 'bin')

                );

                $mediaFile->move($this->getParameter('kernel.project_dir') . '/public/uploads/media', $newFilename);
                $tweet->setMedia($newFilename);
            }

            $entityManager->persist($tweet);
            $entityManager->flush();
            return $this->redirectBack($request);
        }

        return $this->render('tweet/index.html.twig', [
            'tweets' => $tweetRepository->findAllOrderedByIdDesc(),
            'form' => $form->createView(),
            'onglet' => $onglet,
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

        return $this->redirectBack($request);
    }


    // AFFICHE LES COMMENTAIRES D'UN TWEET
    #[Route('/{id}/commentaires', name: 'app_commentaire_show', methods: ['GET'])]
    public function showComment(Tweet $tweet, int $id, TweetRepository $tweetRepository): Response
    {
        $commentaires = $tweet->getCommentaires();
        $commentTweet = $tweetRepository->find($id);

        if (!$commentTweet) {
            return $this->redirectToRoute('error_page');
        }

        return $this->render('commentaire/show.html.twig', [
            'tweet' => $tweet,
            'commentaires' => $commentaires,
        ]);
    }

    // LIKE UN TWEET

    #[Route('/{id}/like', name: 'app_tweet_like', methods: ['POST'])]
    public function like(Tweet $tweet, EntityManagerInterface $entityManager, Request $request,): Response
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

        return $this->redirectBack($request);
    }

    // RETWEET UN TWEET

    #[Route('/{id}/retweet', name: 'app_tweet_retweet', methods: ['POST'])]
    public function retweet(Tweet $tweet, EntityManagerInterface $entityManager, Request $request,): Response
    {
        $user = $this->getUser();

        $existingRetweet = $entityManager->getRepository(Retweet::class)->findOneBy([
            'tweet' => $tweet,
            'user' => $user,
        ]);

        if ($existingRetweet) {

            $entityManager->remove($existingRetweet);
        } else {

            $retweet = new Retweet();
            $retweet->setTweet($tweet);
            $retweet->setUser($user);

            $entityManager->persist($retweet);
        }

        $entityManager->flush();

        return $this->redirectBack($request);
    }

    // AFFICHER DETAIL D'UN TWEET
    #[Route('/{id}', name: 'app_tweet_show', methods: ['GET'])]
    public function show(int $id, TweetRepository $tweetRepository, EntityManagerInterface $em): Response
    {
        $tweet = $tweetRepository->find($id);

        if (!$tweet) {
            // Redirection personnalisée vers une page d'erreur
            return $this->render('error/errorPage.html.twig', [
                'id' => $id,
            ], new Response('', 404));
        }

        $tweet->incrementViews();
        $author = $tweet->getUser();
        $em->flush();

        return $this->render('tweet/show.html.twig', [
            'tweet' => $tweet,
            'user' => $author,
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

            return $this->redirectToRoute('app_tweet_show', ['id' => $tweet->getId()]);
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

        return $this->redirectToRoute('app_tweet_index');
    }


    public function redirectBack(Request $request, string $fallbackRoute = 'app_tweet_index'): RedirectResponse
    {
        // 1) on regarde s'il y a un "redirect" envoyé par le formulaire (voir plus bas)
        $target = $request->request->get('redirect')
            ?? $request->query->get('redirect')
            ?? $request->headers->get('referer'); // 2) sinon on prend le Referer

        // 3) sécurité: on n'autorise que les URLs locales
        if ($target) {
            // URL relative => OK
            if (!preg_match('#^https?://#i', $target)) {
                return $this->redirect($target);
            }
            // URL absolue => vérifier le host
            $host = parse_url($target, PHP_URL_HOST);
            if ($host === $request->getHost()) {
                return $this->redirect($target);
            }
        }

        // Fallback si rien ou externe
        return $this->redirectToRoute($fallbackRoute);
    }
}
