<?php

namespace App\Entity;

use App\Repository\AdminEventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminEventRepository::class)
 */
class AdminEvent
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
    private $id_A_E;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_Event_Loisir;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_Formation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAE(): ?int
    {
        return $this->id_A_E;
    }

    public function setIdAE(int $id_A_E): self
    {
        $this->id_A_E = $id_A_E;

        return $this;
    }

    public function getIdEventLoisir(): ?int
    {
        return $this->id_Event_Loisir;
    }

    public function setIdEventLoisir(?int $id_Event_Loisir): self
    {
        $this->id_Event_Loisir = $id_Event_Loisir;

        return $this;
    }

    public function getIdFormation(): ?int
    {
        return $this->id_Formation;
    }

    public function setIdFormation(?int $id_Formation): self
    {
        $this->id_Formation = $id_Formation;

        return $this;
    }
}