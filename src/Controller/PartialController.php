<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Form\TweetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartialController extends AbstractController
{
    #[Route('/tweet/modal-form', name: 'tweet_modal_form')]
    public function tweetModalForm(Security $security): Response
    {

        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);

        return $this->render('tweet/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
