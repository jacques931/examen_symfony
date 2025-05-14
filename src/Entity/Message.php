<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?\DateTime $dateheure = null;

    /**
     * @var Collection<int, Rendus>
     */
    #[ORM\ManyToMany(targetEntity: Rendus::class, mappedBy: 'messages')]
    private Collection $renduses;

    #[ORM\ManyToOne]
    private ?User $emetteur = null;

    #[ORM\ManyToOne]
    private ?User $destinataire = null;

    public function __construct()
    {
        $this->renduses = new ArrayCollection();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateheure(): ?\DateTime
    {
        return $this->dateheure;
    }

    public function setDateheure(\DateTime $dateheure): static
    {
        $this->dateheure = $dateheure;

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
            $rendus->addMessage($this);
        }

        return $this;
    }

    public function removeRendus(Rendus $rendus): static
    {
        if ($this->renduses->removeElement($rendus)) {
            $rendus->removeMessage($this);
        }

        return $this;
    }

    public function getEmetteur(): ?User
    {
        return $this->emetteur;
    }

    public function setEmetteur(?User $emetteur): static
    {
        $this->emetteur = $emetteur;

        return $this;
    }

    public function getDestinataire(): ?User
    {
        return $this->destinataire;
    }

    public function setDestinataire(?User $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }
}
