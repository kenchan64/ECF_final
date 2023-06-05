<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?int $defaultGuests = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $allergies = null;

    public function getId(): ?int
    {
        return $this->id ?: null;
    }

    public function getEmail(): ?string
    {
        return $this->email ?: null;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getDefaultGuests(): ?int
    {
        return $this->defaultGuests ?: null;
    }

    public function setDefaultGuests(int $defaultGuests): self
    {
        $this->defaultGuests = $defaultGuests;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies ?: null;
    }

    public function setAllergies(string $allergies): self
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getRoles(): array
    {
        // You can hardcode roles if they are not dynamic, or retrieve them from your database if they are.
        // It should return an array of roles, e.g., ['ROLE_USER']
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        // If you are using bcrypt or argon for hashing, you don't need a real salt
        // Return null or a string
        return null;
    }

    public function eraseCredentials()
    {
        // If any sensitive information is stored in your User object, clear it here
        // If not, you can leave this method empty
    }

    public function getUserIdentifier(): string
    {
        // Return the email as the identifier
        return $this->getEmail();
    }

}
