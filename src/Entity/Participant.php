<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idP;

    /**
     * @ORM\ManyToOne(targetEntity=Freelancer::class, inversedBy="participants")
     */
    private $idF;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeU;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="participants")
     */
    private $idFO;

    /**
     * @ORM\ManyToOne(targetEntity=EventLoisir::class, inversedBy="participants")
     */
    private $idE;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeE;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="participants")
     */
    private $idS;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdF(): ?Freelancer
    {
        return $this->idF;
    }

    public function setIdF(?Freelancer $idF): self
    {
        $this->idF = $idF;

        return $this;
    }


    public function getTypeU(): ?string
    {
        return $this->typeU;
    }

    public function setTypeU(string $typeU): self
    {
        $this->typeU = $typeU;

        return $this;
    }

    public function getIdFO(): ?Formation
    {
        return $this->idFO;
    }

    public function setIdFO(?Formation $idFO): self
    {
        $this->idFO = $idFO;

        return $this;
    }

    public function getIdE(): ?EventLoisir
    {
        return $this->idE;
    }

    public function setIdE(?EventLoisir $idE): self
    {
        $this->idE = $idE;

        return $this;
    }

    public function getTypeE(): ?string
    {
        return $this->typeE;
    }

    public function setTypeE(string $typeE): self
    {
        $this->typeE = $typeE;

        return $this;
    }

    public function getIdS(): ?Societe
    {
        return $this->idS;
    }

    public function setIdS(?Societe $idS): self
    {
        $this->idS = $idS;

        return $this;
    }
}