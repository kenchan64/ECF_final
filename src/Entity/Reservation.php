<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\RestaurantSettings;
use App\Repository\RestaurantSettingsRepository;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'nb_couverts', type: Types::INTEGER)]
    private ?int $nbCouverts = null;

    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(name: 'heure', type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure = null;

    #[ORM\Column(name: 'allergies', type: Types::TEXT, nullable: true)]
    private ?string $allergies = null;

    #[ORM\ManyToOne(targetEntity: RestaurantSettings::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: "restaurant_settings_id", referencedColumnName: "id")]

    private ?RestaurantSettings $restaurantSettings;

    public function __construct()
    {
        $this->restaurantSettings = new RestaurantSettings();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbCouverts(): ?int
    {
        return $this->nbCouverts;
    }

    public function setNbCouverts(int $nbCouverts): self
    {
        $this->nbCouverts = $nbCouverts;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): self
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getRestaurantSettings(): ?RestaurantSettings
    {
        return $this->restaurantSettings;
    }

    public function setRestaurantSettings(?RestaurantSettings $restaurantSettings): self
    {
        $this->restaurantSettings = $restaurantSettings;
        return $this;
    }
}
