<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Form\UserSearchType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LikeRepository;
use App\Repository\RetweetRepository;
use App\Repository\TweetRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/')]
#[IsGranted('ROLE_USER')]
final class ProfilController extends AbstractController
{
    #[Route('/user/{id}/delete-banniere', name: 'user_delete_banniere', methods: ['POST'])]
    public function deleteBanierre(User $user, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_banniere' . $user->getId(), $request->request->get('_token'))) {
            $bannerPath = $this->getParameter('banners_directory') . '/' . $user->getProfilBanierre();
            if (file_exists($bannerPath)) {
                @unlink($bannerPath);
            }
            $user->setProfilBanierre(null);
            $em->flush();
            $this->addFlash('success', 'Bannière supprimée !');
        }
        return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
    }

    #[Route('/profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function index(RetweetRepository $retweetRepository, TweetRepository $tweetRepository, CommentaireRepository $commentaireRepository, LikeRepository $likeRepository, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('error_page');
        };

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Création du formulaire d'avatar
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();
            $bannerFile = $form->get('profilBanierre')->getData();

            if ($avatarFile) {
                $newFilename = uniqid() . '.' . $avatarFile->guessExtension();
                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors de l'envoi du fichier.");
                }
                $user->setAvatar($newFilename);
            }
            if ($bannerFile) {
                $newFilename = uniqid() . '.' . $bannerFile->guessExtension();
                try {
                    $bannerFile->move(
                        $this->getParameter('banners_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors de l'envoi du fichier.");
                }
                $user->setProfilBanierre($newFilename);
            }
            if ($this->isCsrfTokenValid('delete_banniere' . $user->getId(), $request->request->get('_token'))) {
                $bannerPath = $this->getParameter('banners_directory') . '/' . $user->getProfilBanierre();
                if (file_exists($bannerPath)) {
                    @unlink($bannerPath);
                }
                $user->setProfilBanierre(null);
                $this->addFlash('success', 'Bannière supprimée !');
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "Profil mise à jour !");
            return $this->redirectToRoute('app_profil');
        }

        $tweets = $tweetRepository->findByUserOrderedByIdDesc($user);
        $likeTweets = $likeRepository->findTweetsLikedByUser($user);
        $commentTweets = $commentaireRepository->findByUserOrderedByIdDesc($user);
        $retweetTweets = $retweetRepository->findTweetsRetweetByUser($user);
        return $this->render('profil/index.html.twig', [
            "user" => $user,
            "tweets" => $tweets,
            'likeTweets' => $likeTweets,
            "form" => $form->createView(),
            'commentTweets' => $commentTweets,
            'retweetTweets' => $retweetTweets,
        ]);
    }

    // Recherche d'un profil

    #[Route('/profil/search', name: 'profil_user_search')]
    public function searchByName(Request $request, UserRepository $userRepository)
    {
        $name = $request->get("searchProfil");
        // dd($name) = utilisé pour tester : affiche le contenu de la variable et arrête le script (dump and die);

        // Récupérer dans la bdd le user dont le nom=$name
        $user = $userRepository->findOneBy(['username' => $name]);

        // puis rediriger sur la route profil_user avec l'id en paramètre

        if ($user) {
            return $this->redirectToRoute('profil_user', [
                'id' => $user->getId(),
            ]);
        } else {
            return $this->redirectToRoute('error_profil');
        }
    }

    #[Route('/profil/{id}', name: 'profil_user')]
    public function show(
        int $id,
        TweetRepository $tweetRepository,
        LikeRepository $likeRepository,
        CommentaireRepository $commentaireRepository,
        RetweetRepository $retweetRepository,
        UserRepository $userRepository
    ): Response {

        $user = $userRepository->find($id);

        if (!$user) {
            return $this->redirectToRoute('error_page');
        };

        if ($this->getUser() && $this->getUser() === $user) {
            return $this->redirectToRoute('app_profil');
        }

        $tweets        = $tweetRepository->findBy(['user' => $user], ['dateTweet' => 'DESC']);
        $likeTweets    = $likeRepository->findTweetsLikedByUser($user);
        $commentTweets = $commentaireRepository->findBy(['user' => $user], ['dateComment' => 'DESC']);
        $retweetTweets = $retweetRepository->findTweetsRetweetByUser($user);

        return $this->render('profil/show.html.twig', [
            'user' => $user,
            'tweets' => $tweetRepository->findBy(['user' => $user]),
            'likeTweets' => $likeRepository->findTweetsLikedByUser($user),
            'commentTweets'  => $commentTweets,
            'retweetTweets'  => $retweetTweets,
            'tweets'         => $tweets,
            'likeTweets'     => $likeTweets

        ]);
    }
    
    #[Route('/profil/{id}/following', name: 'profil_user_followings')]
    public function followings(User $user): Response
    {
        return $this->render('profil/followings.html.twig', [
            'user' => $user,
            'people' => $user->getFollowings(),
        ]);
    }

    #[Route('/profil/{id}/followers', name: 'profil_user_followers')]
    public function followers(User $user): Response
    {
        return $this->render('profil/followers.html.twig', [
            'user' => $user,
            'people' => $user->getFollowers(),
        ]);
    }
}
