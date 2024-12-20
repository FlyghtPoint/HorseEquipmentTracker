<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
// use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
// use Symfony\Component\Security\Core\User\UserInterface;

/** A reservation of an equipment. */
#[ORM\Entity]
#[ApiResource(
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage: 50
)]
#[ApiFilter(SearchFilter::class, properties: [
    'status' => 'exact',
    'type' => 'exact',
    'user.email' => 'partial',
    'equipment.name' => 'partial'
])]
#[ApiFilter(DateFilter::class, properties: ['start_date', 'end_date'])]
#[ApiFilter(OrderFilter::class, properties: ['start_date', 'status'])]
class Reservation
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['equipment:read'])]
    #[Assert\Type(
        type: \DateTimeInterface::class,
        message: 'The value {{ value }} is not a valid datetime.'
    )]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['equipment:read'])]
    #[Assert\NotNull(message: 'End date cannot be null')]
    #[Assert\Type(
        type: \DateTimeInterface::class,
        message: 'The value {{ value }} is not a valid datetime.'
    )]
    #[Assert\Expression(
        "this.getStartDate() === null or this.getStartDate() < this.getEndDate()",
        message: 'End date must be after start date'
    )]    
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(type: 'string',length: 20)]
    #[Groups(['equipment:read'])]
    #[Assert\NotBlank(message: 'Status cannot be blank')]
    #[Assert\Choice(
        choices: ['pending', 'accepted', 'canceled'],
        message: 'Status must be one of: {{ choices }}'
    )]
    private string $status;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['equipment:read'])]
    #[Assert\NotBlank(message: 'Type cannot be blank')]
    #[Assert\Choice(
        choices: ['loan', 'maintenance', 'repair'],
        message: 'Type must be one of: {{ choices }}'
    )]
    private string $type;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['equipment:read'])]
    #[Assert\NotNull(message: 'User must be specified')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Equipment::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Equipment must be specified')]
    private ?Equipment $equipment = null;

    /**
     * @var Collection<int, Movement>
     */
    #[ORM\OneToMany(targetEntity: Movement::class, mappedBy: 'reservation')]
    private Collection $movements;

    public function __construct()
    {
        $this->movements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): static
    {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * @return Collection<int, Movement>
     */
    public function getMovements(): Collection
    {
        return $this->movements;
    }

    public function addMovement(Movement $movement): static
    {
        if (!$this->movements->contains($movement)) {
            $this->movements->add($movement);
            $movement->setReservation($this);
        }

        return $this;
    }

    public function removeMovement(Movement $movement): static
    {
        if ($this->movements->removeElement($movement)) {
            // set the owning side to null (unless already changed)
            if ($movement->getReservation() === $this) {
                $movement->setReservation(null);
            }
        }

        return $this;
    }
}
