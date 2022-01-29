<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaisieAdresseRepository")
 */
class SaisieAdresse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
    
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     */
    private $rayon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRayon(): ?int
    {
        return $this->rayon;
    }

    public function setRayon(int $rayon): self
    {
        $this->rayon = $rayon;

        return $this;
    }
}
