<?php

namespace App\Entity;

use App\Repository\ReponseCondidatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReponseCondidatRepository::class)
 */
class ReponseCondidat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class)
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity=Reponse::class)
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     * @Assert\NotBlank(message="le  reponse est obligatoir")
     */
    private $reponse;

    /**
     * @ORM\ManyToOne(targetEntity=ListReponsesCondidat::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $listReponsesCondidat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getListReponsesCondidat(): ?ListReponsesCondidat
    {
        return $this->listReponsesCondidat;
    }

    public function setListReponsesCondidat(?ListReponsesCondidat $listReponsesCondidat): self
    {
        $this->listReponsesCondidat = $listReponsesCondidat;

        return $this;
    }
}
