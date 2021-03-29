<?php

namespace App\Entity;

use App\Repository\FreelancerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

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

    /**
     * @ORM\OneToMany(targetEntity=DemandeEmploi::class, mappedBy="Freelancer")
     */
    private $demandeEmplois;

    /**
     * @ORM\OneToMany(targetEntity=DemandeStage::class, mappedBy="Freelancer")
     */
    private $demandeStages;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $compte_facebook;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $compte_linkedin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $compte_twitter;



    /**
     * @ORM\Column(type="integer")
     */
    private $viewsNb;

    /**
     * @ORM\Column(type="integer")
     */
    private $etat;


    public function __construct()
    {
        $this->societe = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->demandeEmplois = new ArrayCollection();
        $this->demandeStages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->$id = $id;

        return $this;
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

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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


    public function getViewsNb(): ?string
    {
        return 5;
    }

    public function setComptesReseauxSociaux(string $comptes_reseaux_sociaux): self
    {
        $this->comptes_reseaux_sociaux = $comptes_reseaux_sociaux;

        return $this;
    }


    public function setCompteFacebook(string $compte_facebook): self
    {
        $this->compte_facebook = $compte_facebook;

        return $this;
    }

    public function getCompteLinkedin(): ?string
    {
        return $this->compte_linkedin;
    }

    public function getCompteFacebook(): ?string
    {
        return $this->compte_facebook;
    }

    public function setCompteLinkedin(string $compte_linkedin): self
    {
        $this->compte_linkedin = $compte_linkedin;

        return $this;
    }

    public function getCompteTwitter(): ?string
    {
        return $this->compte_twitter;
    }

    public function setCompteTwitter(string $compte_twitter): self
    {
        $this->compte_twitter = $compte_twitter;

        return $this;
    }


    public function setViewsNb(int $viewsNb): self
    {
        $this->viewsNb = $viewsNb;

        return $this;
    }




    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

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
            $demandeEmploi->setFreelancer($this);
        }

        return $this;
    }

    public function removeDemandeEmploi(DemandeEmploi $demandeEmploi): self
    {
        if ($this->demandeEmplois->removeElement($demandeEmploi)) {
            // set the owning side to null (unless already changed)
            if ($demandeEmploi->getFreelancer() === $this) {
                $demandeEmploi->setFreelancer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DemandeStage[]
     */
    public function getDemandeStages(): Collection
    {
        return $this->demandeStages;
    }

    public function addDemandeStage(DemandeStage $demandeStage): self
    {
        if (!$this->demandeStages->contains($demandeStage)) {
            $this->demandeStages[] = $demandeStage;
            $demandeStage->setFreelancer($this);
        }

        return $this;
    }

    public function removeDemandeStage(DemandeStage $demandeStage): self
    {
        if ($this->demandeStages->removeElement($demandeStage)) {
            // set the owning side to null (unless already changed)
            if ($demandeStage->getFreelancer() === $this) {
                $demandeStage->setFreelancer(null);
            }
        }

        return $this;
    }
}
