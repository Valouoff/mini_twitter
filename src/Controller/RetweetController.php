<?php

namespace App\Controller;

use App\Entity\Retweet;
use App\Form\RetweetType;
use App\Repository\RetweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/retweet')]
#[IsGranted('ROLE_USER')]
final class RetweetController extends AbstractController
{
    #[Route(name: 'app_retweet_index', methods: ['GET'])]
    public function index(RetweetRepository $retweetRepository): Response
    {
        return $this->redirectToRoute('app_tweet_index');
    }

    #[Route('/new', name: 'app_retweet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $retweet = new Retweet();
        $form = $this->createForm(RetweetType::class, $retweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($retweet);
            $entityManager->flush();

            return $this->redirectToRoute('app_retweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('retweet/new.html.twig', [
            'retweet' => $retweet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_retweet_show', methods: ['GET'])]
    public function show(Retweet $retweet): Response
    {
        return $this->render('retweet/show.html.twig', [
            'retweet' => $retweet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_retweet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Retweet $retweet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RetweetType::class, $retweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_retweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('retweet/edit.html.twig', [
            'retweet' => $retweet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_retweet_delete', methods: ['POST'])]
    public function delete(Request $request, Retweet $retweet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $retweet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($retweet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_retweet_index', [], Response::HTTP_SEE_OTHER);
    }
}
