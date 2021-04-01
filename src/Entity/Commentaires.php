<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentairesRepository::class)
 */
class Commentaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\NotBlank(message="la description est obligatoire")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_com;

    /**
     * @ORM\ManyToOne(targetEntity=Publications::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_pub;

    /**
     * @ORM\ManyToOne(targetEntity=Freelancer::class)
     */
    private $id_util;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class)
     */
    private $societe;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->date_com;
    }

    public function setDateCom(\DateTimeInterface $date_com): self
    {
        $this->date_com = $date_com;

        return $this;
    }

    public function getIdPub(): ?Publications
    {
        return $this->id_pub;
    }

    public function setIdPub(?Publications $id_pub): self
    {
        $this->id_pub = $id_pub;

        return $this;
    }

    public function getIdUtil(): ?Freelancer
    {
        return $this->id_util;
    }

    public function setIdUtil(?Freelancer $id_util): self
    {
        $this->id_util = $id_util;

        return $this;
    }

    public function getId_Util(): ?Freelancer
    {
        return $this->id_util;
    }

    public function setId_Util(?Freelancer $id_util): self
    {
        $this->id_util = $id_util;

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): self
    {
        $this->societe = $societe;

        return $this;
    }
}
