<?php

namespace App\Entity;

use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="idS")
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity=Formation::class, mappedBy="idSo")
     */
    private $formations;

    /**
     * @ORM\OneToMany(targetEntity=EventLoisir::class, mappedBy="idSo")
     */
    private $eventLoisirs;

    public function __construct()
    {
        $this->freelancers = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->eventLoisirs = new ArrayCollection();
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

    /**
     * @return Collection|Participant[]
     */
    public function getTypeU(): Collection
    {
        return $this->typeU;
    }

    public function addparticipants(Participant $typeU): self
    {
        if (!$this->participants->contains($typeU)) {
            $this->participants[] = $typeU;
            $typeU->setIdS($this);
        }

        return $this;
    }

    public function removeTypeU(Participant $typeU): self
    {
        if ($this->typeU->removeElement($typeU)) {
            // set the owning side to null (unless already changed)
            if ($typeU->getIdS() === $this) {
                $typeU->setIdS(null);
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
            $formation->setIdSo($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getIdSo() === $this) {
                $formation->setIdSo(null);
            }
        }

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
}
