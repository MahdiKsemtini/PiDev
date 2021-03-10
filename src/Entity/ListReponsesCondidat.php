<?php

namespace App\Entity;

use App\Repository\ListReponsesCondidatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListReponsesCondidatRepository::class)
 */
class ListReponsesCondidat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=ReponseCondidat::class, mappedBy="listReponsesCondidat")
     */
    private $reponses;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="listReponsesCondidats")
     */
    private $quiz;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ReponseCondidat[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(ReponseCondidat $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setListReponsesCondidat($this);
        }

        return $this;
    }

    public function removeReponse(ReponseCondidat $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getListReponsesCondidat() === $this) {
                $reponse->setListReponsesCondidat(null);
            }
        }

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }
}
