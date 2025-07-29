<?php

namespace App\Entity;

use App\Repository\RetweetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetweetRepository::class)]
class Retweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateRetweet = null;

    #[ORM\ManyToOne(inversedBy: 'retweets')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'retweets')]
    private ?Tweet $tweet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRetweet(): ?\DateTime
    {
        return $this->dateRetweet;
    }

    public function setDateRetweet(\DateTime $dateRetweet): static
    {
        $this->dateRetweet = $dateRetweet;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTweet(): ?Tweet
    {
        return $this->tweet;
    }

    public function setTweet(?Tweet $tweet): static
    {
        $this->tweet = $tweet;

        return $this;
    }
}
