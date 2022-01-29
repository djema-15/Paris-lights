<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransportRepository")
 */
class Transport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Nom_station_transport;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type_transport;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $numero_ligne_transport;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $geo_transport;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse_station_transport;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomStationTransport(): ?string
    {
        return $this->Nom_station_transport;
    }

    public function setNomStationTransport(string $Nom_station_transport): self
    {
        $this->Nom_station_transport = $Nom_station_transport;

        return $this;
    }

    public function getTypeTransport(): ?string
    {
        return $this->type_transport;
    }

    public function setTypeTransport(string $type_transport): self
    {
        $this->type_transport = $type_transport;

        return $this;
    }

    public function getNumeroLigneTransport(): ?string
    {
        return $this->numero_ligne_transport;
    }

    public function setNumeroLigneTransport(string $numero_ligne_transport): self
    {
        $this->numero_ligne_transport = $numero_ligne_transport;

        return $this;
    }

    public function getGeoTransport(): ?string
    {
        return $this->geo_transport;
    }

    public function setGeoTransport(string $geo_transport): self
    {
        $this->geo_transport = $geo_transport;

        return $this;
    }

    public function getAdresseStationTransport(): ?string
    {
        return $this->adresse_station_transport;
    }

    public function setAdresseStationTransport(string $adresse_station_transport): self
    {
        $this->adresse_station_transport = $adresse_station_transport;

        return $this;
    }
}
