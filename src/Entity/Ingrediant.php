<?php

namespace App\Entity;

use App\Repository\IngrediantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: IngrediantRepository::class)]
class Ingrediant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ingrediant:read', 'menu:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ingrediant:read', 'menu:read'])]
    private ?string $titre = null;

    #[ORM\ManyToOne(inversedBy: 'ingrediants')]

    private ?Menu $menu = null;

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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): static
    {
        $this->menu = $menu;

        return $this;
    }
}
