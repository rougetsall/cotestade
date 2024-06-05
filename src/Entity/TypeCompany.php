<?php

namespace App\Entity;

use App\Repository\TypeCompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeCompanyRepository::class)
 */
class TypeCompany
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Adherent::class, mappedBy="typeCompany")
     */
    private $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->name;
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
     * @return Collection|Adherent[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Adherent $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
            $category->setTypeCompany($this);
        }

        return $this;
    }

    public function removeCategory(Adherent $category): self
    {
        if ($this->category->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getTypeCompany() === $this) {
                $category->setTypeCompany(null);
            }
        }

        return $this;
    }
}
