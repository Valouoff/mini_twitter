<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(
        message: "Vous ne pouvez pas publiez un commentaire vide.",
        normalizer: 'trim'
    )]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le commentaire doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le commentaire ne peut pas dépasser {{ limit }} caractères.",
        normalizer: 'trim'
    )]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $dateComment = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    private ?Tweet $tweet = null;

    /**
     * @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'commentaire', orphanRemoval: true, cascade: ['remove'])]
    private Collection $likes;

    /**
     * @var Collection<int, Retweet>
     */
    #[ORM\OneToMany(targetEntity: Retweet::class, mappedBy: 'commentaire')]
    private Collection $retweets;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->retweets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDateComment(): ?\DateTime
    {
        return $this->dateComment;
    }

    public function setDateComment(\DateTime $dateComment): static
    {
        $this->dateComment = $dateComment;

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
    public function setDateCommentValue(): void
    {
        if ($this->dateComment === null) {
            $this->dateComment = new \DateTime();
        }
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setCommentaire($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getCommentaire() === $this) {
                $like->setCommentaire(null);
            }
        }

        return $this;
    }

    public function isLikedBy(User $user): bool
    {
        foreach ($this->getLikes() as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Collection<int, Retweet>
     */
    public function getRetweets(): Collection
    {
        return $this->retweets;
    }

    public function addRetweet(Retweet $retweet): static
    {
        if (!$this->retweets->contains($retweet)) {
            $this->retweets->add($retweet);
            $retweet->setCommentaire($this);
        }

        return $this;
    }

    public function removeRetweet(Retweet $retweet): static
    {
        if ($this->retweets->removeElement($retweet)) {
            // set the owning side to null (unless already changed)
            if ($retweet->getCommentaire() === $this) {
                $retweet->setCommentaire(null);
            }
        }

        return $this;
    }
}
