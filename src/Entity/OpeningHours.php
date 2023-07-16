<?php

namespace App\Entity;

use App\Repository\OpeningHoursRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpeningHoursRepository::class)]
class OpeningHours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $middayOpen = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $middayClose = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $eveningOpen = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $eveningClose = null;

    #[ORM\Column(nullable: true)]
    private ?bool $closed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getMiddayOpen(): ?DateTimeInterface
    {
        return $this->middayOpen;
    }

    public function setMiddayOpen(?DateTimeInterface $middayOpen): self
    {
        $this->middayOpen = $middayOpen;

        return $this;
    }

    public function getMiddayClose(): ?DateTimeInterface
    {
        return $this->middayClose;
    }

    public function setMiddayClose(?DateTimeInterface $middayClose): self
    {
        $this->middayClose = $middayClose;

        return $this;
    }

    public function getEveningOpen(): ?DateTimeInterface
    {
        return $this->eveningOpen;
    }

    public function setEveningOpen(?DateTimeInterface $eveningOpen): self
    {
        $this->eveningOpen = $eveningOpen;

        return $this;
    }

    public function getEveningClose(): ?DateTimeInterface
    {
        return $this->eveningClose;
    }

    public function setEveningClose(?DateTimeInterface $eveningClose): self
    {
        $this->eveningClose = $eveningClose;

        return $this;
    }

    public function isClosed(): ?bool
    {
        return $this->closed;
    }

    public function setClosed(?bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }
}
