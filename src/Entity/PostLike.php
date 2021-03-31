<?php

namespace App\Entity;

use App\Repository\PostLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostLikeRepository::class)
 */
class PostLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Publications::class, inversedBy="likes")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=Freelancer::class, inversedBy="likes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="likes")
     */
    private $societe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Publications
    {
        return $this->post;
    }

    public function setPost(?Publications $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?Freelancer
    {
        return $this->user;
    }

    public function setUser(?Freelancer $user): self
    {
        $this->user = $user;

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
