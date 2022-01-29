<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GeolocalisationRepository")
 */
class Geolocalisation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude_geolocalis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude_geolocalis;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitudeGeolocalis(): ?string
    {
        return $this->latitude_geolocalis;
    }

    public function setLatitudeGeolocalis(?string $latitude_geolocalis): self
    {
        $this->latitude_geolocalis = $latitude_geolocalis;

        return $this;
    }

    public function getLongitudeGeolocalis(): ?string
    {
        return $this->longitude_geolocalis;
    }

    public function setLongitudeGeolocalis(?string $longitude_geolocalis): self
    {
        $this->longitude_geolocalis = $longitude_geolocalis;

        return $this;
    }
}
