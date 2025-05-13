<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['menu:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['menu:read'])]
    private ?string $titre = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    private ?User $userlink = null;

    /**
     * @var Collection<int, Ingrediant>
     */
    #[ORM\OneToMany(targetEntity: Ingrediant::class, mappedBy: 'menu')]
    #[Groups(['menu:read', 'ingrediant:read'])]
    private Collection $ingrediants;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['menu:read'])]
    private ?string $photo = null;

    public function __construct()
    {
        $this->ingrediants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getUserlink(): ?User
    {
        return $this->userlink;
    }

    public function setUserlink(?User $userlink): static
    {
        $this->userlink = $userlink;

        return $this;
    }

    /**
     * @return Collection<int, Ingrediant>
     */
    public function getIngrediants(): Collection
    {
        return $this->ingrediants;
    }

    public function addIngrediant(Ingrediant $ingrediant): static
    {
        if (!$this->ingrediants->contains($ingrediant)) {
            $this->ingrediants->add($ingrediant);
            $ingrediant->setMenu($this);
        }

        return $this;
    }

    public function removeIngrediant(Ingrediant $ingrediant): static
    {
        if ($this->ingrediants->removeElement($ingrediant)) {
            // set the owning side to null (unless already changed)
            if ($ingrediant->getMenu() === $this) {
                $ingrediant->setMenu(null);
            }
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
