<?php

namespace App\Entity;

// use App\Repository\LocationRepository;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/** A location in the warehouse. */
#[ORM\Entity]
#[ApiResource]
class Location
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    #[Groups(['equipment:read'])]
    #[Assert\NotBlank(message: 'Aisle cannot be blank')]
    #[Assert\Length(
        exactly: 2,
        exactMessage: 'Aisle must be exactly {{ limit }} characters long'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z]{2}$/',
        message: 'Aisle must be two uppercase letters'
    )]    
    private ?string $aisle = null;

    #[ORM\Column]
    #[Groups(['equipment:read'])]
    #[Assert\NotNull(message: 'Shelf number cannot be null')]
    #[Assert\Positive(message: 'Shelf number must be a positive integer')]
    #[Assert\LessThanOrEqual(
        value: 100,
        message: 'Shelf number cannot be greater than {{ value }}'
    )]    
    private ?int $shelf_number = null;

    /**
     * @var Collection<int, Equipment>
     */
    #[ORM\OneToMany(targetEntity: Equipment::class, mappedBy: 'location')]
    private Collection $equipment;

    public function __construct()
    {
        $this->equipment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAisle(): ?string
    {
        return $this->aisle;
    }

    public function setAisle(string $aisle): static
    {
        $this->aisle = $aisle;

        return $this;
    }

    #[SerializedName('shelf_number')]
    public function getShelfNumber(): ?int
    {
        return $this->shelf_number;
    }

    public function setShelfNumber(int $shelf_number): static
    {
        $this->shelf_number = $shelf_number;

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipment(): Collection
    {
        return $this->equipment;
    }

    public function addEquipment(Equipment $equipment): static
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment->add($equipment);
            $equipment->setLocation($this);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        if ($this->equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getLocation() === $this) {
                $equipment->setLocation(null);
            }
        }

        return $this;
    }
}
