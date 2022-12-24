<?php

namespace App\Entity;

use App\Repository\WorksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorksRepository::class)]
class Works
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $filesOrlinks = null;

    #[ORM\ManyToOne(inversedBy: 'works')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categories = null;

    #[ORM\ManyToOne(inversedBy: 'works')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'likes', targetEntity: WorksLikes::class)]
    private Collection $worksLikes;

    public function __construct()
    {
        $this->worksLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getFilesOrlinks(): ?string
    {
        return $this->filesOrlinks;
    }

    public function setFilesOrlinks(?string $filesOrlinks): self
    {
        $this->filesOrlinks = $filesOrlinks;

        return $this;
    }

    public function getCategories(): ?Categorie
    {
        return $this->categories;
    }

    public function setCategories(?Categorie $categories): self
    {
        $this->categories = $categories;

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

    /**
     * @return Collection<int, WorksLikes>
     */
    public function getWorksLikes(): Collection
    {
        return $this->worksLikes;
    }

    public function addWorksLike(WorksLikes $worksLike): self
    {
        if (!$this->worksLikes->contains($worksLike)) {
            $this->worksLikes->add($worksLike);
            $worksLike->setLikes($this);
        }

        return $this;
    }

    public function removeWorksLike(WorksLikes $worksLike): self
    {
        if ($this->worksLikes->removeElement($worksLike)) {
            // set the owning side to null (unless already changed)
            if ($worksLike->getLikes() === $this) {
                $worksLike->setLikes(null);
            }
        }

        return $this;
    }

    /**
     * Allows to know a work has been liked by the user.
     *
     * @param User|null $user
     * @return bool
     */
    public function isLikedByUser(User $user): bool
    {
        //dd($this->getWorksLikes()); // elements: []
        foreach ($this->getWorksLikes() as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}
