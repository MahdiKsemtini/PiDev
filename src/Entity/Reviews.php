<?php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewsRepository::class)
 */
class Reviews
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
    private $numberReviews;

    /**
     * @ORM\Column(type="integer")
     */
    private $idGiver;

    /**
     * @ORM\Column(type="integer")
     */
    private $idTaker;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberReviews(): ?int
    {
        return $this->numberReviews;
    }

    public function setNumberReviews(int $numberReviews): self
    {
        $this->numberReviews = $numberReviews;

        return $this;
    }

    public function getIdGiver(): ?int
    {
        return $this->idGiver;
    }

    public function setIdGiver(int $idGiver): self
    {
        $this->idGiver = $idGiver;

        return $this;
    }

    public function getIdTaker(): ?int
    {
        return $this->idTaker;
    }

    public function setIdTaker(int $idTaker): self
    {
        $this->idTaker = $idTaker;

        return $this;
    }
}
