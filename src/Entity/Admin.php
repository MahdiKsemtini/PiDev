<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("admin:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("admin:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("admin:read")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("admin:read")
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("admin:read")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("admin:read")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("admin:read")
     */
    private $etat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("admin:read")
     */
    private $approuve;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("admin:read")
     */
    private $nonapprouve;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }



    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('nom', new Assert\NotBlank([
            'message' => 'Le champ de votre Nom est vide',
        ]));
        $metadata->addPropertyConstraint('nom', new Assert\Length([
            'min' => 3,
            'max'=>15,
            'minMessage' => 'Votre Nom doit comporter au moins "{{ limit }}" caractères',
            'maxMessage' => 'Votre Nom ne peut pas comporter plus de "{{ limit }}" caractères',
        ]));

        $metadata->addPropertyConstraint('prenom', new Assert\NotBlank([
            'message' => 'Le champ de votre Prenom est vide',
        ]));
        $metadata->addPropertyConstraint('prenom', new Assert\Length([
            'min' => 3,
            'max'=>15,
            'minMessage' => 'Votre Prenom doit comporter au moins {{ limit }} caractères',
            'maxMessage' => 'Votre Prénom ne peut pas comporter plus de {{ limit }} caractères',
        ]));

        $metadata->addPropertyConstraint('login', new Assert\NotBlank([
            'message' => 'Le champ de login est vide',
        ]));
        $metadata->addPropertyConstraint('login', new Assert\Length([
            'min' => 4,
            'max'=> 40,
            'minMessage' => 'Votre login doit comporter au moins "{{ limit }}" caractères',
            'maxMessage' => 'Votre login ne peut pas comporter plus de "{{ limit }}" caractères',
        ]));
        $metadata->addPropertyConstraint('login', new Assert\Email([
            'message' => 'The login {{ value }} is not a valid email.',
        ]));

        $metadata->addPropertyConstraint('password', new Assert\NotBlank([
            'message' => 'Le champ de votre Mot De Pass est vide',
        ]));
        $metadata->addPropertyConstraint('password', new Assert\Length([
            'min' => 4,
            'max'=> 20,
            'minMessage' => 'Votre Mot De Passe doit comporter au moins "{{ limit }}" caractères',
            'maxMessage' => 'Votre Mot De Passe ne peut pas comporter plus de "{{ limit }}" caractères',
        ]));

    }

    public function getApprouve(): ?int
    {
        return $this->approuve;
    }

    public function setApprouve(?int $approuve): self
    {
        $this->approuve = $approuve;

        return $this;
    }

    public function getNonapprouve(): ?int
    {
        return $this->nonapprouve;
    }

    public function setNonapprouve(?int $nonapprouve): self
    {
        $this->nonapprouve = $nonapprouve;

        return $this;
    }
}