<?php

namespace App\Entity;

use App\Repository\AdminEmploiRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminEmploiRepository::class)
 */
class AdminEmploi
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
    private $id_Offre_Emploi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_Offre_Stage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_Demande_Emploi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_Demande_Stage;

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

    public function getIdOffreEmploi(): ?int
    {
        return $this->id_Offre_Emploi;
    }

    public function setIdOffreEmploi(?int $id_Offre_Emploi): self
    {
        $this->id_Offre_Emploi = $id_Offre_Emploi;

        return $this;
    }

    public function getIdOffreStage(): ?int
    {
        return $this->id_Offre_Stage;
    }

    public function setIdOffreStage(?int $id_Offre_Stage): self
    {
        $this->id_Offre_Stage = $id_Offre_Stage;

        return $this;
    }

    public function getIdDemandeEmploi(): ?int
    {
        return $this->id_Demande_Emploi;
    }

    public function setIdDemandeEmploi(?int $id_Demande_Emploi): self
    {
        $this->id_Demande_Emploi = $id_Demande_Emploi;

        return $this;
    }

    public function getIdDemandeStage(): ?int
    {
        return $this->id_Demande_Stage;
    }

    public function setIdDemandeStage(?int $id_Demande_Stage): self
    {
        $this->id_Demande_Stage = $id_Demande_Stage;

        return $this;
    }
}
