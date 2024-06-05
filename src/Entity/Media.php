<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
class Media
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $legend;
    /**
     * Not an ORM column
     */
    private $file;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $files;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="media")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

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

    public function getLegend(): ?string
    {
        return $this->legend;
    }

    public function setLegend(string $legend): self
    {
        $this->legend = $legend;

        return $this;
    }
    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
        
        return $this;
    }
    public function getFiles(): ?string
    {
        return $this->files;
    }

    public function setFiles(string $files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getUser(): ?Adherent
    {
        return $this->user;
    }

    public function setUser(?Adherent $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
