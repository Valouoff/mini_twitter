<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/')]
#[IsGranted('ROLE_USER')]
class FollowController extends AbstractController
{
    #[Route('/user/{id}/follow', name: 'user_follow', methods: ['POST'])]
    public function follow(User $user, EntityManagerInterface $em, Request $request): Response
    {
        $currentUser = $this->getUser();
        /** @var User $currentUser */

        if (!$currentUser) {
            throw $this->createAccessDeniedException("Vous devez être connecté pour suivre quelqu'un.");
        }

        if ($currentUser !== $user && !$currentUser->isFollowing($user)) {
            $submittedToken = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('follow_user' . $user->getId(), $submittedToken)) {
                throw $this->createAccessDeniedException('Token CSRF invalide.');
            }
            $currentUser->follow($user);
            $em->flush();
            $this->addFlash('success', "Tu suis maintenant {$user->getUsername()} !");
        }
        return $this->redirectToRoute('profil_user', ['id' => $user->getId()]);
    }

    #[Route('/user/{id}/unfollow', name: 'user_unfollow', methods: ['POST'])]
    public function unfollow(User $user, EntityManagerInterface $em, Request $request): Response
    {
        $currentUser = $this->getUser();
        /** @var User $currentUser */

        if (!$currentUser) {
            throw $this->createAccessDeniedException("Vous devez être connecté pour vous désabonner.");
        }

        if ($currentUser !== $user && $currentUser->isFollowing($user)) {
            $submittedToken = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('unfollow_user' . $user->getId(), $submittedToken)) {
                throw $this->createAccessDeniedException('Token CSRF invalide.');
            }
            $currentUser->unfollow($user);
            $em->flush();
            $this->addFlash('success', "Tu ne suis plus {$user->getUsername()} !");
        }
        return $this->redirectToRoute('profil_user', ['id' => $user->getId()]);
    }
}
