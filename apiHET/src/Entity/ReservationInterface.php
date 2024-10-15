<?php

namespace App\Entity;

interface ReservationInterface
{
    public function getId(): ?int;
    public function getStartDate(): ?\DateTimeInterface;
    public function setStartDate(?\DateTimeInterface $start_date): static;
    public function getEndDate(): ?\DateTimeInterface;
    public function setEndDate(\DateTimeInterface $end_date): static;
}
