<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
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
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $texteReclamation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dateReclamation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_utilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_utilisateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTexteReclamation(): ?string
    {
        return $this->texteReclamation;
    }

    public function setTexteReclamation(string $texteReclamation): self
    {
        $this->texteReclamation = $texteReclamation;

        return $this;
    }

    public function getDateReclamation(): ?string
    {
        return $this->dateReclamation;
    }

    public function setDateReclamation(string $dateReclamation): self
    {
        $this->dateReclamation = $dateReclamation;

        return $this;
    }

    public function getEmailUtilisateur(): ?string
    {
        return $this->email_utilisateur;
    }

    public function setEmailUtilisateur(string $email_utilisateur): self
    {
        $this->email_utilisateur = $email_utilisateur;

        return $this;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nom_utilisateur;
    }

    public function setNomUtilisateur(string $nom_utilisateur): self
    {
        $this->nom_utilisateur = $nom_utilisateur;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
