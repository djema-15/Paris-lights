<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VelibRepository")
 */
class Velib
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $code_station;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Nom_de_la_station;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $geo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse_velib;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeStation(): ?int
    {
        return $this->code_station;
    }

    public function setCodeStation(int $code_station): self
    {
        $this->code_station = $code_station;

        return $this;
    }

    public function getNomDeLaStation(): ?string
    {
        return $this->Nom_de_la_station;
    }

    public function setNomDeLaStation(string $Nom_de_la_station): self
    {
        $this->Nom_de_la_station = $Nom_de_la_station;

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

    public function getAdresseVelib(): ?string
    {
        return $this->adresse_velib;
    }

    public function setAdresseVelib(string $adresse_velib): self
    {
        $this->adresse_velib = $adresse_velib;

        return $this;
    }
}
