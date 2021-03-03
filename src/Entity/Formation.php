<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idF;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="tt")
     */

    private $Description;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="tt")
     */
    private $DateDebut;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="tt")
     */
    private $DateFin;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="tt")
     */
    private $Lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="tt")
     */
    private $Domaine;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="tt")
     */
    private $Montant;

    /**
     * @ORM\Column(type="integer")
     *
     */
    private $idU;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="tt")
     */
    private $Labelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Etat;



    public function getIdF(): ?int
    {
        return $this->idF;
    }

    public function setIdF(string $idF): self
    {
        $this->idF = $idF;

        return $this;
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

    public function getDateDebut():?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin():?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->Lieu;
    }

    public function setLieu(string $Lieu): self
    {
        $this->Lieu = $Lieu;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->Domaine;
    }

    public function setDomaine(string $Domaine): self
    {
        $this->Domaine = $Domaine;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): self
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getIdU(): ?int
    {
        return $this->idU;
    }

    public function setIdU(int $idU): self
    {
        $this->idU = $idU;

        return $this;
    }

    public function getLabelle(): ?string
    {
        return $this->Labelle;
    }

    public function setLabelle(string $Labelle): self
    {
        $this->Labelle = $Labelle;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->Etat;
    }

    public function setEtat(bool $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }
}
