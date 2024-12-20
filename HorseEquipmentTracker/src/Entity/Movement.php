<?php

namespace App\Entity;

use MovementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
// use App\Repository\MovementRepository;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/** Describes the movements inside of the stock (in or out) */
#[ORM\Entity]
#[ApiResource]
class Movement
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank(message: 'Type cannot be blank')]
    #[Assert\Choice(
        choices: ['in', 'out'],
        message: 'Type must be either "in" or "out"'
    )]
    private string $type;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'Date cannot be null')]
    #[Assert\Type(
        type: \DateTimeInterface::class,
        message: 'The value {{ value }} is not a valid datetime.'
    )] 
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'movements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Reservation must be specified')]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
