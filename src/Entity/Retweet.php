<?php

namespace App\Entity;

use App\Repository\RetweetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetweetRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Retweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateRetweet = null;

    #[ORM\ManyToOne(inversedBy: 'retweets')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'retweets')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Tweet $tweet = null;

    #[ORM\ManyToOne(inversedBy: 'retweets')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Commentaire $commentaire = null;

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

    #[ORM\PrePersist]
    public function setDateRetweetValue(): void
    {
        if ($this->dateRetweet === null) {
            $this->dateRetweet = new \DateTime();
        }
    }

    public function getCommentaire(): ?Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?Commentaire $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
