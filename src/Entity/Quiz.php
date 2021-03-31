<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le nom de quiz est obligatoir")
     */
    private $nom_quiz;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="le nombre de questions est obligatoir")
     * @Assert\GreaterThan(0, message="le nombre de question doit etre positif")
     * @Assert\LessThan(50, message="le nombre de question doit etre inferieure a 50")
     */
    private $nomb_question;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="quiz_id")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=ListReponsesCondidat::class, mappedBy="quiz", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $listReponsesCondidats;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_societe;

    /**
     * @ORM\ManyToOne(targetEntity=OffreEmploi::class)
     */
    private $offre_emploi;







    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->listReponsesCondidats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomQuiz(): ?string
    {
        return $this->nom_quiz;
    }

    public function setNomQuiz(string $nom_quiz): self
    {
        $this->nom_quiz = $nom_quiz;

        return $this;
    }

    public function getNombQuestion(): ?int
    {
        return $this->nomb_question;
    }

    public function setNombQuestion(int $nomb_question): self
    {
        $this->nomb_question = $nomb_question;

        return $this;
    }



    public function getIdSociete(): ?string
    {
        return $this->id_societe;
    }

    public function setIdSociete(string $id_societe): self
    {
        $this->id_societe = $id_societe;

        return $this;
    }


    /**
     * @return Collection|Question[]
     */

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestions(Question $questions): self
    {
        if (!$this->questions->contains($questions)) {
            $this->questions[] = $questions;
            $questions->setQuizId($this);
        }

        return $this;
    }

    public function removeQuestions(Question $questions): self
    {
        if ($this->questions->removeElement($questions)) {
            // set the owning side to null (unless already changed)
            if ($questions->getQuizId() === $this) {
                $questions->getQuizId(null);
            }
        }

        return $this;
    }

    public function getOffreEmploi(): ?OffreEmploi
    {
        return $this->offre_emploi;
    }

    public function setOffreEmploi(?OffreEmploi $offre_emploi): self
    {
        $this->offre_emploi = $offre_emploi;

        return $this;
    }



}