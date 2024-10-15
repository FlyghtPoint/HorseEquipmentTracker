<?php

namespace App\Entity;

interface EquipmentInterface
{
    public function getId(): ?int;
    public function getName(): ?string;
    public function setName(string $name): self;
}
