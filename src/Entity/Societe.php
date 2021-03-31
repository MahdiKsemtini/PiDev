<?php

namespace App\Entity;

use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use http\Message;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=SocieteRepository::class)
 */
class Societe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
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
    private $mot_de_pass;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo_de_profile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status_juridique;

    /**
     * @ORM\ManyToMany(targetEntity=Freelancer::class, mappedBy="societe")
     */
    private $freelancers;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewsNb;

    /**
     * @ORM\OneToMany(targetEntity=EventLoisir::class, mappedBy="idSo")
     */
    private $eventLoisirs;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="idS")
     */
    private $participants;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date_Creation;

    /**
     * @ORM\OneToMany(targetEntity=OffreEmploi::class, mappedBy="societe")
     */
    private $offreEmplois;

    /**
     * @ORM\OneToMany(targetEntity=OffreStage::class, mappedBy="societe")
     */
    private $offreStages;

    /**
     * @ORM\Column(type="integer")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="societe")
     */
    private $likes;

    public function __construct()
    {
        $this->idfreelancers = new ArrayCollection();
        $this->eventLoisirs = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->offreEmplois = new ArrayCollection();
        $this->offreStages = new ArrayCollection();
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

    public function getMotDePass(): ?string
    {
        return $this->mot_de_pass;
    }

    public function setMotDePass(string $mot_de_pass): self
    {
        $this->mot_de_pass = $mot_de_pass;

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

    public function getStatusJuridique(): ?string
    {
        return $this->status_juridique;
    }

    public function setStatusJuridique(string $status_juridique): self
    {
        $this->status_juridique = $status_juridique;

        return $this;
    }

    /**
     * @return Collection|Freelancer[]
     */
    public function getFreelancers(): Collection
    {
        return $this->freelancers;
    }


    public function addFreelancer(Freelancer $freelancer): self
    {
        if (!$this->freelancers->contains($freelancer)) {
            $this->freelancers[] = $freelancer;
            $freelancer->addSociete($this);
        }

        return $this;
    }

    public function removeFreelancer(Freelancer $freelancer): self
    {
        if ($this->freelancers->removeElement($freelancer)) {
            $freelancer->removeSociete($this);
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
            'minMessage' => 'Votre Nom doit comporter au moins "{{limit}}" caractères',
            'maxMessage' => 'Votre Nom ne peut pas comporter plus de "{{limit}}" caractères',
        ]));

        $metadata->addPropertyConstraint('adresse', new Assert\NotBlank([
            'message' => 'Le champ de votre Adresse est vide',
        ]));
        $metadata->addPropertyConstraint('adresse', new Assert\Length([
            'min' => 4,
            'max'=> 15,
            'minMessage' => 'Votre Adresse doit comporter au moins "{{limit}}" caractères',
            'maxMessage' => 'Votre Adresse ne peut pas comporter plus de "{{limit}}" caractères',
        ]));

        $metadata->addPropertyConstraint('email', new Assert\NotBlank([
            'message' => 'Le champ de votre Email est vide',
        ]));
        $metadata->addPropertyConstraint('email', new Assert\Length([
            'min' => 4,
            'max'=> 20,
            'minMessage' => 'Votre Email doit comporter au moins "{{limit}}" caractères',
            'maxMessage' => 'Votre Email ne peut pas comporter plus de "{{limit}}" caractères',
        ]));
        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => 'The Email "{{ value }}" is not a valid email.',
        ]));

        $metadata->addPropertyConstraint('mot_de_pass', new Assert\NotBlank([
            'message' => 'Le champ de votre Mot De Pass est vide',
        ]));
        $metadata->addPropertyConstraint('mot_de_pass', new Assert\Length([
            'min' => 4,
            'max'=> 20,
            'minMessage' => 'Votre Mot De Pass doit comporter au moins "{{limit}}" caractères',
            'maxMessage' => 'Votre Mot De Pass ne peut pas comporter plus de "{{limit}}" caractères',
        ]));

        $metadata->addPropertyConstraint('photo_de_profile', new Assert\NotBlank([
            'message' => 'Tu dois mettre une Photo De Profile',
        ]));

        $metadata->addPropertyConstraint('status_juridique', new Assert\NotBlank([
            'message' => 'Le champ de votre Status Juridique est vide',
        ]));
        $metadata->addPropertyConstraint('status_juridique', new Assert\Length([
            'min' => 2,
            'max'=> 20,
            'minMessage' => 'Votre Status Juridique doit comporter au moins "{{limit}}" caractères',
            'maxMessage' => 'Votre Status Juridique ne peut pas comporter plus de "{{limit}}" caractères',
        ]));

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
            $eventLoisir->setIdSo($this);
        }

        return $this;
    }

    public function removeEventLoisir(EventLoisir $eventLoisir): self
    {
        if ($this->eventLoisirs->removeElement($eventLoisir)) {
            // set the owning side to null (unless already changed)
            if ($eventLoisir->getIdSo() === $this) {
                $eventLoisir->setIdSo(null);
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
            $participant->setIdS($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getIdS() === $this) {
                $participant->setIdS(null);
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

    /**
     * @return Collection|OffreEmploi[]
     */
    public function getOffreEmplois(): Collection
    {
        return $this->offreEmplois;
    }

    public function addOffreEmploi(OffreEmploi $offreEmploi): self
    {
        if (!$this->offreEmplois->contains($offreEmploi)) {
            $this->offreEmplois[] = $offreEmploi;
            $offreEmploi->setSociete($this);
        }

        return $this;
    }

    public function removeOffreEmploi(OffreEmploi $offreEmploi): self
    {
        if ($this->offreEmplois->removeElement($offreEmploi)) {
            // set the owning side to null (unless already changed)
            if ($offreEmploi->getSociete() === $this) {
                $offreEmploi->setSociete(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OffreStage[]
     */
    public function getOffreStages(): Collection
    {
        return $this->offreStages;
    }

    public function addOffreStage(OffreStage $offreStage): self
    {
        if (!$this->offreStages->contains($offreStage)) {
            $this->offreStages[] = $offreStage;
            $offreStage->setSociete($this);
        }

        return $this;
    }

    public function removeOffreStage(OffreStage $offreStage): self
    {
        if ($this->offreStages->removeElement($offreStage)) {
            // set the owning side to null (unless already changed)
            if ($offreStage->getSociete() === $this) {
                $offreStage->setSociete(null);
            }
        }

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
            $like->setSociete($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getSociete() === $this) {
                $like->setSociete(null);
            }
        }

        return $this;
    }
}
