<?php

namespace App\Entity;
use App\Form\EmploiUpdateType;

use App\Repository\OffreEmploiRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OffreEmploiRepository::class)
 */
class OffreEmploi
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("emploi")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer un nom de projet svp")
     * @Groups("emploi")
     */
    private $nomProjet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer les compétences aquises")
     * @Groups("emploi")
     */
    private $competences;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer la description")
     * @Groups("emploi")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer le domaine")
     * @Groups("emploi")
     */
    private $domaine;

    /**
     * @ORM\Column(type="string", length=255)   
     * @Assert\NotBlank(message="inserer le fichier")
     * @Groups("emploi")
     */
    private $fichier;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer un salaire")
     * @Groups("emploi")
     */
    private $salaire;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="offreEmplois")
     */
    private $societe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProjet(): ?string
    {
        return $this->nomProjet;
    }

    public function setNomProjet(string $nomProjet): self
    {
        $this->nomProjet = $nomProjet;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getSalaire(): ?string
    {
        return $this->salaire;
    }

    public function setSalaire(string $salaire): self
    {
        $this->salaire = $salaire;

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
}
