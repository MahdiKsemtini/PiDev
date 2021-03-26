<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class ReponseList
{    /**
 * @ORM\Id
 * @ORM\GeneratedValue
 * @ORM\Column(type="integer")
 */
    private $id;
    private $reponses;


    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    /**
     * @param \App\Entity\Reponse $reponse
     * @return $this
     */
    public function addReponse(\App\Entity\Reponse $reponse)
    {
        $this->reponses[] = $reponse;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getReponses(): ArrayCollection
    {
        return $this->reponses;
    }



}
