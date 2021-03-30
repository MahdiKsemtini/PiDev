<?php

namespace App\Entity;

use App\Repository\EventLoisirRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventLoisirRepository::class)
 */
class EventLoisir
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("event:read")
     */
    private $id;



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="tt")
     * @Groups("event:read")
     */
    private $Description;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="tt")
     * @Groups("event:read")
     */
    private $DateDebut;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="tt")
     * @Groups("event:read")
     */
    private $DateFin;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="tt")
     * @Groups("event:read")
     */
    private $Lieu;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="tt")
     * @Groups("event:read")
     */
    private $Labelle;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="tt")
     * @Groups("event:read")
     */
    private $NbParticipant;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="tt")
     * @Groups("event:read")
     */
    private $Domaine;



    /**
     * @ORM\Column(type="boolean")
     */
    private $Etat;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="idE")
     */
    private $participants;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lng;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;

    /**
     * @ORM\ManyToOne(targetEntity=Freelancer::class, inversedBy="eventLoisirs")
     */
    private $idFr;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="eventLoisirs")
     */
    private $idSo;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
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
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin():?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->Lieu;
    }

    public function setLieu(string $Lieu): self
    {
        $this->Lieu = $Lieu;

        return $this;
    }

    public function getLabelle(): ?string
    {
        return $this->Labelle;
    }

    public function setLabelle(string $Labelle): self
    {
        $this->Labelle = $Labelle;

        return $this;
    }

    public function getNbParticipant(): ?int
    {
        return $this->NbParticipant;
    }

    public function setNbParticipant(int $NbParticipant): self
    {
        $this->NbParticipant = $NbParticipant;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->Domaine;
    }

    public function setDomaine(string $Domaine): self
    {
        $this->Domaine = $Domaine;

        return $this;
    }



    public function getEtat(): ?bool
    {
        return $this->Etat;
    }

    public function setEtat(bool $Etat): self
    {
        $this->Etat = $Etat;

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
            $participant->setIdE($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getIdE() === $this) {
                $participant->setIdE(null);
            }
        }

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getIdFr(): ?Freelancer
    {
        return $this->idFr;
    }

    public function setIdFr(?Freelancer $idFr): self
    {
        $this->idFr = $idFr;

        return $this;
    }

    public function getIdSo(): ?Societe
    {
        return $this->idSo;
    }

    public function setIdSo(?Societe $idSo): self
    {
        $this->idSo = $idSo;

        return $this;
    }
}