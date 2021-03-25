<?php

namespace App\Entity;

use App\Repository\AdminReclamtionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminReclamtionRepository::class)
 */
class AdminReclamtion
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
    private $id_A_R;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_Reclamation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAR(): ?int
    {
        return $this->id_A_R;
    }

    public function setIdAR(int $id_A_R): self
    {
        $this->id_A_R = $id_A_R;

        return $this;
    }

    public function getIdReclamtion(): ?int
    {
        return $this->id_Reclamation;
    }

    public function setIdReclamtion(int $id_Reclamtion): self
    {
        $this->id_Reclamation = $id_Reclamtion;

        return $this;
    }
}
