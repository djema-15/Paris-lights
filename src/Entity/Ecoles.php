<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EcolesRepository")
 */
class Ecoles
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
    private $nom_ecole;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse_ecole;

    /**
     * @ORM\Column(type="integer")
     */
    private $code_postale_ecole;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $geo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_ecole;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEcole(): ?string
    {
        return $this->nom_ecole;
    }

    public function setNomEcole(string $nom_ecole): self
    {
        $this->nom_ecole = $nom_ecole;

        return $this;
    }

    public function getAdresseEcole(): ?string
    {
        return $this->adresse_ecole;
    }

    public function setAdresseEcole(string $adresse_ecole): self
    {
        $this->adresse_ecole = $adresse_ecole;

        return $this;
    }

    public function getCodePostaleEcole(): ?int
    {
        return $this->code_postale_ecole;
    }

    public function setCodePostaleEcole(int $code_postale_ecole): self
    {
        $this->code_postale_ecole = $code_postale_ecole;

        return $this;
    }

    public function getGeo(): ?string
    {
        return $this->geo;
    }

    public function setGeo(string $geo): self
    {
        $this->geo = $geo;

        return $this;
    }

    public function getTypeEcole(): ?string
    {
        return $this->type_ecole;
    }

    public function setTypeEcole(string $type_ecole): self
    {
        $this->type_ecole = $type_ecole;

        return $this;
    }
}
