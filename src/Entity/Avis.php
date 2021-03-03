<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AvisRepository::class)
 */
class Avis
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
    private $textAvis;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_utilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTextAvis(): ?string
    {
        return $this->textAvis;
    }

    public function setTextAvis(string $textAvis): self
    {
        $this->textAvis = $textAvis;

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
}
