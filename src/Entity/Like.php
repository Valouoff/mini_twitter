<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateLiker = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateLiker(): ?\DateTime
    {
        return $this->dateLiker;
    }

    public function setDateLiker(\DateTime $dateLiker): static
    {
        $this->dateLiker = $dateLiker;

        return $this;
    }
}
