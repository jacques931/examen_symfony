<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column(length: 255)]
    private ?string $preseentation = null;

    #[ORM\Column(length: 255)]
    private ?string $support = null;

    #[ORM\Column(length: 255)]
    private ?string $nature = null;

    #[ORM\Column(length: 255)]
    private ?string $url_document = null;

    #[ORM\ManyToOne(inversedBy: 'ressource')]
    private ?Etapes $etapes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getPreseentation(): ?string
    {
        return $this->preseentation;
    }

    public function setPreseentation(string $preseentation): static
    {
        $this->preseentation = $preseentation;

        return $this;
    }

    public function getSupport(): ?string
    {
        return $this->support;
    }

    public function setSupport(string $support): static
    {
        $this->support = $support;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): static
    {
        $this->nature = $nature;

        return $this;
    }

    public function getUrlDocument(): ?string
    {
        return $this->url_document;
    }

    public function setUrlDocument(string $url_document): static
    {
        $this->url_document = $url_document;

        return $this;
    }

    public function getEtapes(): ?Etapes
    {
        return $this->etapes;
    }

    public function setEtapes(?Etapes $etapes): static
    {
        $this->etapes = $etapes;

        return $this;
    }
}
