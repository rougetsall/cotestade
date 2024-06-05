<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 */
class Adherent
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
     * @ORM\Column(type="string", length=255)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="text", length=1000)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCompany::class, inversedBy="category")
     */
    private $typeCompany;

    /**
     * Not an ORM column
     */
    private $file;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="adherent")
     */
    private $events;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="integer")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $passworduser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $presentation;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="user")
     */
    private $media;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $background;

     /**
     * Not an ORM column
     */
    private $fileback;

    /**
     * @ORM\OneToMany(targetEntity=Privatisation::class, mappedBy="adherent")
     */
    private $privatisations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagemobile;

    /**
     * Not an ORM column
     */
    private $filemoile;


    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->privatisations = new ArrayCollection();
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

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTypeCompany(): ?TypeCompany
    {
        return $this->typeCompany;
    }

    public function setTypeCompany(?TypeCompany $typeCompany): self
    {
        $this->typeCompany = $typeCompany;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

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
    public function getFileback()
    {
        return $this->fileback;
    }

    public function setFileback($fileback)
    {
        $this->fileback= $fileback;
        
        return $this;
    }
    public function getFilemobile()
    {
        return $this->fileback;
    }

    public function setFilemobile($fileback)
    {
        $this->fileback= $fileback;
        
        return $this;
    }
    
    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAdherent($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAdherent() === $this) {
                $event->setAdherent(null);
            }
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getPassworduser(): ?string
    {
        return $this->passworduser;
    }

    public function setPassworduser(string $passworduser): self
    {
        $this->passworduser = $passworduser;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setUser($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getUser() === $this) {
                $medium->setUser(null);
            }
        }

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(string $background): self
    {
        $this->background = $background;

        return $this;
    }

    /**
     * @return Collection|Privatisation[]
     */
    public function getPrivatisations(): Collection
    {
        return $this->privatisations;
    }

    public function addPrivatisation(Privatisation $privatisation): self
    {
        if (!$this->privatisations->contains($privatisation)) {
            $this->privatisations[] = $privatisation;
            $privatisation->setAdherent($this);
        }

        return $this;
    }

    public function removePrivatisation(Privatisation $privatisation): self
    {
        if ($this->privatisations->removeElement($privatisation)) {
            // set the owning side to null (unless already changed)
            if ($privatisation->getAdherent() === $this) {
                $privatisation->setAdherent(null);
            }
        }

        return $this;
    }

    public function getImagemobile(): ?string
    {
        return $this->imagemobile;
    }

    public function setImagemobile(?string $imagemobile): self
    {
        $this->imagemobile = $imagemobile;

        return $this;
    }
}
