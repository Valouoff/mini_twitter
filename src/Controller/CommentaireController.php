<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\Like;
use App\Entity\Commentaire;
use App\Entity\Retweet;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commentaire')]
#[IsGranted('ROLE_USER')]
final class CommentaireController extends AbstractController
{



    #[Route(name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->redirectToRoute('app_tweet_index');
    }

    // AFFICHER UN COMMENTAIRE

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET', 'POST'])]
    public function show(int $id, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = $commentaireRepository->find($id);

        if (!$commentaire) {
            return $this->redirectToRoute('error_page');
        }

        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    // MODIFIER UN COMMENTAIRE

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $tweet = $commentaire->getTweet();
            return $this->redirectToRoute('app_tweet_show', ['id' => $tweet->getId()]);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }


    // SUPPRIMER UN COMMENTAIRE

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectBack($request);
    }

    // LIKE UN COMMENTAIRE

    #[Route('/{id}/like', name: 'app_commentaire_like', methods: ['POST'])]
    public function like(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $existingLike = $entityManager->getRepository(Like::class)->findOneBy([
            'commentaire' => $commentaire,
            'user' => $user,
        ]);

        if ($existingLike) {

            $entityManager->remove($existingLike);
        } else {

            $like = new Like();
            $like->setCommentaire($commentaire);
            $like->setUser($user);

            $entityManager->persist($like);
        }

        $entityManager->flush();

        return $this->redirectBack($request);
    }

    // RETWEET UN COMMENTAIRE

    #[Route('/{id}/retweet', name: 'app_commentaire_retweet', methods: ['POST'])]
    public function retweet(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $existingRetweet = $entityManager->getRepository(Retweet::class)->findOneBy([
            'commentaire' => $commentaire,
            'user' => $user,
        ]);

        if ($existingRetweet) {

            $entityManager->remove($existingRetweet);
        } else {

            $retweet = new Retweet();
            $retweet->setCommentaire($commentaire);
            $retweet->setUser($user);

            $entityManager->persist($retweet);
        }

        $entityManager->flush();

        return $this->redirectBack($request);
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
