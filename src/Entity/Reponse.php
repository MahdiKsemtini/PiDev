<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le contenu de reponse est obligatoir")
     */
    private $contenu_rep;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $id_ques;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenuRep(): ?string
    {
        return $this->contenu_rep;
    }

    public function setContenuRep(string $contenu_rep): self
    {
        $this->contenu_rep = $contenu_rep;

        return $this;
    }

    public function getIdQues(): ?Question
    {
        return $this->id_ques;
    }

    public function setIdQues(?Question $id_ques): self
    {
        $this->id_ques = $id_ques;

        return $this;
    }
}
