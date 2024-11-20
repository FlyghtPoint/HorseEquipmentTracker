<?php
// src/Dto/UserDto.php
namespace App\Dto;

class UserDto
{
    private ?int $id = null;
    private ?string $email = null;
    private array $roles = [];

    // Getters & Setters
    public function getId(): ?int 
    {
        return $this->id;
    }

    public function getEmail(): ?string 
    {
        return $this->email;
    }

    public function getRoles(): array 
    {
        return $this->roles;
    }

    // Méthode de création depuis un tableau
    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->id = $data['id'] ?? null;
        $dto->email = $data['email'] ?? null;
        $dto->roles = $data['roles'] ?? [];
        return $dto;
    }
}

// Similaire pour Equipment, Reservation, etc.