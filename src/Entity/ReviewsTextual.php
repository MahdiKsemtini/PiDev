<?php

namespace App\Entity;

use App\Repository\ReviewsTextualRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewsTextualRepository::class)
 */
class ReviewsTextual
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
    private $Description;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class)
     */
    private $societe;

    /**
     * @ORM\ManyToOne(targetEntity=Freelancer::class)
     */
    private $freelancer;

    /**
     * @ORM\Column(type="integer")
     */
    private $idTaker;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeTaker;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getFreelancer(): ?Freelancer
    {
        return $this->freelancer;
    }

    public function setFreelancer(?Freelancer $freelancer): self
    {
        $this->freelancer = $freelancer;

        return $this;
    }

    public function getIdTaker(): ?int
    {
        return $this->idTaker;
    }

    public function setIdTaker(int $idTaker): self
    {
        $this->idTaker = $idTaker;

        return $this;
    }

    public function getTypeTaker(): ?string
    {
        return $this->typeTaker;
    }

    public function setTypeTaker(string $typeTaker): self
    {
        $this->typeTaker = $typeTaker;

        return $this;
    }
}
