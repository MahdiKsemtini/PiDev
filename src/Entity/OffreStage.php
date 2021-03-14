<?php

namespace App\Entity;

use App\Repository\OffreStageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OffreStageRepository::class)
 */
class OffreStage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer un nom de projet svp")
     */
    private $nomProjet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer des compétences aquises")
     */
    private $competences;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer une description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer un domaine")
     */
    private $domaine;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer un fichier PDF")
     */
    private $fichier;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer une durée de stage")
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer un type de projet")
     */
    private $typeStage;

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

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getTypeStage(): ?string
    {
        return $this->typeStage;
    }

    public function setTypeStage(string $typeStage): self
    {
        $this->typeStage = $typeStage;

        return $this;
    }
}
