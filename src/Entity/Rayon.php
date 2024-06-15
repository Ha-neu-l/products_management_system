<?php

namespace App\Entity;

use App\Repository\RayonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RayonRepository::class)]
class Rayon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'rayon', targetEntity: Category::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'rayon', targetEntity: Memeber::class)]
    private Collection $memebers;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->memebers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setRayon($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getRayon() === $this) {
                $category->setRayon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Memeber>
     */
    public function getMemebers(): Collection
    {
        return $this->memebers;
    }

    public function addMemeber(Memeber $memeber): self
    {
        if (!$this->memebers->contains($memeber)) {
            $this->memebers->add($memeber);
            $memeber->setRayon($this);
        }

        return $this;
    }

    public function removeMemeber(Memeber $memeber): self
    {
        if ($this->memebers->removeElement($memeber)) {
            // set the owning side to null (unless already changed)
            if ($memeber->getRayon() === $this) {
                $memeber->setRayon(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->name;
    }
}
