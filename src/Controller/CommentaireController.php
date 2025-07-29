<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\Like;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commentaire')]
final class CommentaireController extends AbstractController
{
    #[Route(name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    // NOUVEAU COMMENTAIRE

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $tweet = $tweetRepository->find($id);
        if (!$tweet) {
            throw $this->createNotFoundException('Tweet introuvable.');
        }

        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentaire->setUser($this->getUser());

            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
            'tweet' => $tweet,

        ]);
    }

    // AFFICHER UN COMMENTAIRE

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
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

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }

    // LIKE UN COMMENTAIRE

    #[Route('/{id}/like', name: 'app_commentaire_like', methods: ['POST'])]
    public function like(Commentaire $commentaire, EntityManagerInterface $entityManager): Response
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

        return $this->redirectToRoute('app_profil');
    }
}
