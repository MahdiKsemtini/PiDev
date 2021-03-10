<?php

namespace App\Entity;

use App\Repository\DemandeEmploiRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DemandeEmploiRepository::class)
 */
class DemandeEmploi
{


    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="votre salaire est vide")
     *@Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $salaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="votre champs dipolme est vide")
     * @Assert\Length( min=7 ,max=50, minMessage="diplome {{ value }} superieur a 50 ou inferieur a 7")
     */
    private $diplome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="votre cv est vide")
     */
    private $cv;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="blank!")
     * @Assert\NotNull(message="null")
     * @Assert\Length( min=7 ,max=100, minMessage="description {{ value }} superieur a 50 ou inferieur a 7")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotBlank(message="votre champs date de creation est vide")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="votre champs domaine est vide")
     * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $domaine;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="votre champs nom de societe est vide")
     * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $nomsociete;

    /**
     * @ORM\ManyToOne(targetEntity=OffreEmploi::class, inversedBy="demandeEmplois")
     */
    private $OffreEmploi;




    /**
     * DemandeEmploi constructor.

     */
    public function __construct()
    {

    }


    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }
    public function getNomSociete(): ?string
    {
        return $this->nomsociete;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(string $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function setNomSociete(string $nom_societe): self
    {
        $this->nomsociete = $nom_societe;

        return $this;
    }

    public function getOffreEmploi(): ?OffreEmploi
    {
        return $this->OffreEmploi;
    }

    public function setOffreEmploi(?OffreEmploi $OffreEmploi): self
    {
        $this->OffreEmploi = $OffreEmploi;

        return $this;
    }


}
