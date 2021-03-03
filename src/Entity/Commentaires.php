<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="integer")
     */
    private $id_pub;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_util;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\NotBlank(message="la description est obligatoire")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_com;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPub(): ?int
    {
        return $this->id_pub;
    }

    public function setIdPub(int $id_pub): self
    {
        $this->id_pub = $id_pub;

        return $this;
    }

    public function getId_Util(): ?int
    {
        return $this->id_util;
    }

    public function setId_Util(int $id_util): self
    {
        $this->id_util = $id_util;

        return $this;
    }

    public function getIdUtil(): ?int
    {
        return $this->id_util;
    }

    public function setIdUtil(int $id_util): self
    {
        $this->id_util = $id_util;

        return $this;
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
}
