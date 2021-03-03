<?php

namespace App\Entity;

use App\Repository\FreelancerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=FreelancerRepository::class)
 */
class Freelancer
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mot_de_passe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo_de_profile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $competences;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $langues;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comptes_reseaux_sociaux;

    /**
     * @ORM\ManyToMany(targetEntity=Societe::class, inversedBy="freelancers")
     */
    private $societe;

    public function __construct()
    {
        $this->societe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getPhotoDeProfile(): ?string
    {
        return $this->photo_de_profile;
    }

    public function setPhotoDeProfile(string $photo_de_profile): self
    {
        $this->photo_de_profile = $photo_de_profile;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(string $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    public function getLangues(): ?string
    {
        return $this->langues;
    }

    public function setLangues(string $langues): self
    {
        $this->langues = $langues;

        return $this;
    }

    public function getComptesReseauxSociaux(): ?string
    {
        return $this->comptes_reseaux_sociaux;
    }

    public function setComptesReseauxSociaux(string $comptes_reseaux_sociaux): self
    {
        $this->comptes_reseaux_sociaux = $comptes_reseaux_sociaux;

        return $this;
    }

    /**
     * @return Collection|Societe[]
     */
    public function getSociete(): Collection
    {
        return $this->societe;
    }

    public function addSociete(Societe $societe): self
    {
        if (!$this->societe->contains($societe)) {
            $this->societe[] = $societe;
        }

        return $this;
    }

    public function removeSociete(Societe $societe): self
    {
        $this->societe->removeElement($societe);

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('nom', new Assert\NotBlank([
            'message' => 'Le champ de votre Nom est vide',
        ]));
        $metadata->addPropertyConstraint('nom', new Assert\Length([
            'min' => 3,
            'max'=>15,
            'minMessage' => 'Votre Nom doit comporter au moins {{limit}} caractères',
            'maxMessage' => 'Votre Nom ne peut pas comporter plus de {{limit}} caractères',
        ]));

        $metadata->addPropertyConstraint('prenom', new Assert\NotBlank([
            'message' => 'Le champ de votre Prenom est vide',
        ]));
        $metadata->addPropertyConstraint('prenom', new Assert\Length([
            'min' => 3,
            'max'=>15,
            'minMessage' => 'Votre Prenom doit comporter au moins {{limit}} caractères',
            'maxMessage' => 'Votre Prénom ne peut pas comporter plus de {{limit}} caractères',
        ]));

        $metadata->addPropertyConstraint('email', new Assert\NotBlank([
            'message' => 'Le champ de votre Email est vide',
        ]));
        $metadata->addPropertyConstraint('email', new Assert\Length([
            'min' => 4,
            'max'=> 40,
            'minMessage' => 'Votre Email doit comporter au moins {{limit}} caractères',
            'maxMessage' => 'Votre Email ne peut pas comporter plus de "{{limit}}" caractères',
        ]));
        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => 'The Email "{{ value }}" is not a valid email.',
        ]));

        $metadata->addPropertyConstraint('mot_de_passe', new Assert\NotBlank([
            'message' => 'Le champ de votre Mot De Pass est vide',
        ]));
        $metadata->addPropertyConstraint('mot_de_passe', new Assert\Length([
            'min' => 4,
            'max'=> 20,
            'minMessage' => 'Votre Mot De Pass doit comporter au moins {{limit}} caractères',
            'maxMessage' => 'Votre Mot De Pass ne peut pas comporter plus de {{limit}} caractères',
        ]));

    }
}
