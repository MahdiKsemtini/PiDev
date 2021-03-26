<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le contenu de question est obligatoir")
     */
    private $contenu_ques;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="le nombre de reonses est obligatoir")
     * @Assert\GreaterThan(0, message="le nombre de reponse doit etre positif")
     * @Assert\LessThan(5, message="le nombre de question doit etre inferieure a 5")
     */
    private $nomb_rep;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="id_ques", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $reponses;

    /**
     * @ORM\OneToOne(targetEntity=Reponse::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $rep_just;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $quiz_id;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenuQues(): ?string
    {
        return $this->contenu_ques;
    }

    public function setContenuQues(string $contenu_ques): self
    {
        $this->contenu_ques = $contenu_ques;

        return $this;
    }

    public function getNombRep(): ?int
    {
        return $this->nomb_rep;
    }

    public function setNombRep(int $nomb_rep): self
    {
        $this->nomb_rep = $nomb_rep;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setIdQues($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getIdQues() === $this) {
                $reponse->setIdQues(null);
            }
        }

        return $this;
    }

    public function getRepJust(): ?Reponse
    {
        return $this->rep_just;
    }

    public function setRepJust(Reponse $rep_just): self
    {
        $this->rep_just = $rep_just;

        return $this;
    }

    public function getQuizId(): ?Quiz
    {
        return $this->quiz_id;
    }

    public function setQuizId(?Quiz $quiz_id): self
    {
        $this->quiz_id = $quiz_id;

        return $this;
    }
}
