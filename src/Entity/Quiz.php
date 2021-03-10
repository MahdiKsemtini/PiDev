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

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuizId($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuizId() === $this) {
                $question->setQuizId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNomQuiz();
    }

    /**
     * @return Collection|ListReponsesCondidat[]
     */
    public function getListReponsesCondidats(): Collection
    {
        return $this->listReponsesCondidats;
    }

    public function addListReponsesCondidat(ListReponsesCondidat $listReponsesCondidat): self
    {
        if (!$this->listReponsesCondidats->contains($listReponsesCondidat)) {
            $this->listReponsesCondidats[] = $listReponsesCondidat;
            $listReponsesCondidat->setQuiz($this);
        }

        return $this;
    }

    public function removeListReponsesCondidat(ListReponsesCondidat $listReponsesCondidat): self
    {
        if ($this->listReponsesCondidats->removeElement($listReponsesCondidat)) {
            // set the owning side to null (unless already changed)
            if ($listReponsesCondidat->getQuiz() === $this) {
                $listReponsesCondidat->setQuiz(null);
            }
        }

        return $this;
    }
}
