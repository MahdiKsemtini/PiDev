<?php

namespace App\Entity;

use App\Repository\FreelancerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @ORM\ManyToMany(targetEntity=Societe::class, inversedBy="freelancers")
     */
    private $societe;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewsNb;

    /**
     * @ORM\Column(type="integer")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=EventLoisir::class, mappedBy="idFr")
     */
    private $eventLoisirs;

    /**
     * @ORM\OneToMany(targetEntity=Formation::class, mappedBy="idFr")
     */
    private $formations;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="idF")
     */
    private $participants;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date_Creation;

    protected $captchaCode;

    /**
     * @ORM\OneToMany(targetEntity=DemandeEmploi::class, mappedBy="Freelancer")
     */
    private $demandeEmplois;

    /**
     * @ORM\OneToMany(targetEntity=DemandeStage::class, mappedBy="Freelancer")
     */
    private $demandeStages;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="user")
     */
    private $likes;


    public function __construct()
    {
        $this->societe = new ArrayCollection();
        $this->eventLoisirs = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->demandeEmplois = new ArrayCollection();
        $this->demandeStages = new ArrayCollection();
        $this->likes = new ArrayCollection();
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



    public function getCompteFacebook(): ?string
    {
        return $this->compte_facebook;
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

    public function getViewsNb(): ?int
    {
        return $this->viewsNb;
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
     * @return Collection|EventLoisir[]
     */
    public function getEventLoisirs(): Collection
    {
        return $this->eventLoisirs;
    }

    public function addEventLoisir(EventLoisir $eventLoisir): self
    {
        if (!$this->eventLoisirs->contains($eventLoisir)) {
            $this->eventLoisirs[] = $eventLoisir;
            $eventLoisir->setIdFr($this);
        }

        return $this;
    }

    public function removeEventLoisir(EventLoisir $eventLoisir): self
    {
        if ($this->eventLoisirs->removeElement($eventLoisir)) {
            // set the owning side to null (unless already changed)
            if ($eventLoisir->getIdFr() === $this) {
                $eventLoisir->setIdFr(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Formation[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->setIdFr($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getIdFr() === $this) {
                $formation->setIdFr(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setIdF($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getIdF() === $this) {
                $participant->setIdF(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?string
    {
        return $this->date_Creation;
    }

    public function setDateCreation(string $date_Creation): self
    {
        $this->date_Creation = $date_Creation;

        return $this;
    }
    public function getDate_Creation(): ?string
    {
        return $this->date_Creation;
    }

    public function setDate_Creation(string $date_Creation): self
    {
        $this->date_Creation = $date_Creation;

        return $this;
    }

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('nom', new Assert\NotBlank([
            'message' => 'Le champ de votre Nom est vide',
        ]));
        $metadata->addPropertyConstraint('nom', new Assert\Length([
            'min' => 3,
            'max'=>15,
            'minMessage' => 'Votre Nom doit comporter au moins "{{ limit }}" caractères',
            'maxMessage' => 'Votre Nom ne peut pas comporter plus de "{{ limit }}" caractères',
        ]));

        $metadata->addPropertyConstraint('prenom', new Assert\NotBlank([
            'message' => 'Le champ de votre Prenom est vide',
        ]));
        $metadata->addPropertyConstraint('prenom', new Assert\Length([
            'min' => 3,
            'max'=>15,
            'minMessage' => 'Votre Prenom doit comporter au moins "{{ limit }}" caractères',
            'maxMessage' => 'Votre Prénom ne peut pas comporter plus de "{{ limit }}" caractères',
        ]));

        $metadata->addPropertyConstraint('email', new Assert\NotBlank([
            'message' => 'Le champ de votre Email est vide',
        ]));
        $metadata->addPropertyConstraint('email', new Assert\Length([
            'min' => 4,
            'max'=> 40,
            'minMessage' => 'Votre Email doit comporter au moins "{{ limit }}" caractères',
            'maxMessage' => 'Votre Email ne peut pas comporter plus de "{{ limit }}" caractères',
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
            'minMessage' => 'Votre Mot De Pass doit comporter au moins "{{ limit }}" caractères',
            'maxMessage' => 'Votre Mot De Pass ne peut pas comporter plus de "{{ limit }}" caractères',
        ]));
        $metadata->addPropertyConstraint('compte_facebook', new Assert\NotBlank([
            'message' => 'Le champ de votre Compte Facebook est vide',
        ]));
        $metadata->addPropertyConstraint('compte_twitter', new Assert\NotBlank([
            'message' => 'Le champ de votre compte Twitter est vide',
        ]));
        $metadata->addPropertyConstraint('compte_linkedin', new Assert\NotBlank([
            'message' => 'Le champ de votre Compte linkedin est vide',
        ]));

    }

    /**
     * @return Collection|PostLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PostLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }




}
