<?php

namespace App\Entity;
use App\Form\EmploiUpdateType;

use App\Repository\OffreEmploiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
;

/**
 * @ORM\Entity(repositoryClass=OffreEmploiRepository::class)
 */
class OffreEmploi
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
     * @Assert\NotBlank(message="inserer les compétences aquises")
     */
    private $competences;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer la description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer le domaine")
     */
    private $domaine;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fichier;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="inserer un salaire")
     */
    private $salaire;

    /**
     * @ORM\OneToMany(targetEntity=DemandeEmploi::class, mappedBy="OffreEmploi")
     */
    private $demandeEmplois;

    public function __construct()
    {
        $this->demandeEmplois = new ArrayCollection();
    }

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

    /**
     * @return Collection|DemandeEmploi[]
     */
    public function getDemandeEmplois(): Collection
    {
        return $this->demandeEmplois;
    }

    public function addDemandeEmploi(DemandeEmploi $demandeEmploi): self
    {
        if (!$this->demandeEmplois->contains($demandeEmploi)) {
            $this->demandeEmplois[] = $demandeEmploi;
            $demandeEmploi->setOffreEmploi($this);
        }

        return $this;
    }

    public function removeDemandeEmploi(DemandeEmploi $demandeEmploi): self
    {
        if ($this->demandeEmplois->removeElement($demandeEmploi)) {
            // set the owning side to null (unless already changed)
            if ($demandeEmploi->getOffreEmploi() === $this) {
                $demandeEmploi->setOffreEmploi(null);
            }
        }

        return $this;
    }
}
