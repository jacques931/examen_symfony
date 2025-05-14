<?php

namespace App\Entity;

use App\Repository\EtapesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtapesRepository::class)]
class Etapes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptif = null;

    #[ORM\Column(length: 255)]
    private ?string $consignes = null;

    #[ORM\Column]
    private ?int $position = null;

    /**
     * @var Collection<int, Ressource>
     */
    #[ORM\OneToMany(targetEntity: Ressource::class, mappedBy: 'etapes')]
    private Collection $ressource;

    #[ORM\ManyToOne(inversedBy: 'etapes')]
    private ?Parcours $parcours = null;

    /**
     * @var Collection<int, Rendus>
     */
    #[ORM\ManyToMany(targetEntity: Rendus::class, mappedBy: 'etapes')]
    private Collection $renduses;

    public function __construct()
    {
        $this->ressource = new ArrayCollection();
        $this->renduses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): static
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getConsignes(): ?string
    {
        return $this->consignes;
    }

    public function setConsignes(string $consignes): static
    {
        $this->consignes = $consignes;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessource(): Collection
    {
        return $this->ressource;
    }

    public function addRessource(Ressource $ressource): static
    {
        if (!$this->ressource->contains($ressource)) {
            $this->ressource->add($ressource);
            $ressource->setEtapes($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): static
    {
        if ($this->ressource->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getEtapes() === $this) {
                $ressource->setEtapes(null);
            }
        }

        return $this;
    }

    public function getParcours(): ?Parcours
    {
        return $this->parcours;
    }

    public function setParcours(?Parcours $parcours): static
    {
        $this->parcours = $parcours;

        return $this;
    }

    /**
     * @return Collection<int, Rendus>
     */
    public function getRenduses(): Collection
    {
        return $this->renduses;
    }

    public function addRendus(Rendus $rendus): static
    {
        if (!$this->renduses->contains($rendus)) {
            $this->renduses->add($rendus);
            $rendus->addEtape($this);
        }

        return $this;
    }

    public function removeRendus(Rendus $rendus): static
    {
        if ($this->renduses->removeElement($rendus)) {
            $rendus->removeEtape($this);
        }

        return $this;
    }
    
    public function __toString(): string
    {
        return $this->descriptif ?? 'Nouvelle étape';
    }
}
