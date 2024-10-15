<?php

namespace App\Entity;

interface MovementInterface
{
    public function getId(): ?int;
    public function getType(): string;
    public function setType(string $type): self;
    public function getDate(): ?\DateTimeInterface;
    public function setDate(\DateTimeInterface $date): static;
    public function getReservation(): ?ReservationInterface;
    public function setReservation(?ReservationInterface $reservation): static;
}
