<?php

namespace App\Entity;

use App\Repository\WorksLikesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorksLikesRepository::class)]
class WorksLikes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'worksLikes')]
    private ?Works $likes = null;

    #[ORM\ManyToOne(inversedBy: 'worksLikes')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikes(): ?Works
    {
        return $this->likes;
    }

    public function setLikes(?Works $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
