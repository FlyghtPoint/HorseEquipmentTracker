<?php

namespace App\Entity;

// use App\Repository\ConditionRepository;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/** Equipment Condition */
#[ORM\Entity]
#[ORM\Table(name: "`condition`")]
#[ApiResource]
class Condition
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['equipment:read'])]
    #[Assert\NotBlank(message: 'Condition name cannot be blank')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Category name must be at least {{ limit }} characters long',
        maxMessage: 'Category name cannot be longer than {{ limit }} characters'
    )]    
    private ?string $name = null;

    /**
     * @var Collection<int, Equipment>
     */
    #[ORM\OneToMany(targetEntity: Equipment::class, mappedBy: 'eCondition')]
    private Collection $equipment;

    public function __construct()
    {
        $this->equipment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $equipment->setECondition($this);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        if ($this->equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getECondition() === $this) {
                $equipment->setECondition(null);
            }
        }

        return $this;
    }
}
