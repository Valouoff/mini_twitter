<?php

namespace App\Controller;

use App\Entity\Like;
use App\Form\LikeType;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted as AttributeIsGranted;

#[Route('/like')]
#[AttributeIsGranted('ROLE_USER')]
final class LikeController extends AbstractController
{
    #[Route(name: 'app_like_index', methods: ['GET'])]
    public function index(LikeRepository $likeRepository): Response
    {
        return $this->redirectToRoute('app_tweet_index');
    }

    #[Route('/new', name: 'app_like_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $like = new Like();
        $form = $this->createForm(LikeType::class, $like);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($like);
            $entityManager->flush();

            return $this->redirectBack($request);
        }

        return $this->render('like/new.html.twig', [
            'like' => $like,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_like_show', methods: ['GET'])]
    public function show(int $id, LikeRepository $likeRepository): Response
    {

        $like = $likeRepository->find($id);

        if (!$like) {
            return $this->redirectToRoute('error_page');
        }

        return $this->render('like/show.html.twig', [
            'like' => $like,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_like_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LikeType::class, $like);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectBack($request);
        }

        return $this->render('like/edit.html.twig', [
            'like' => $like,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_like_delete', methods: ['POST'])]
    public function delete(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $like->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($like);
            $entityManager->flush();
        }

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
