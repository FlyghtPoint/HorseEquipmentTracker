<?php
// api/src/Entity/Equipment.php
namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
// use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/** Equipment */
#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can add equipment.'
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can edit equipment.'
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: 'Only admins can delete equipment.'
        ),
    ],
    normalizationContext: ['groups' => ['equipment:read']],
    denormalizationContext: ['groups' => ['equipment:write']],
    paginationItemsPerPage: 5,
    paginationMaximumItemsPerPage: 50,
)]
#[ApiFilter(SearchFilter::class, properties: [
    'name' => 'partial',
    'description' => 'partial',
    'category.name' => 'exact',
    'location.aisle' => 'exact',
    'eCondition.name' => 'exact'
])]
#[ApiFilter(OrderFilter::class, properties: ['name', 'category.name'])]
class Equipment
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    #[Groups(['equipment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['equipment:read'])]
    #[Assert\NotBlank(message: 'Equipment name cannot be blank')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Equipment name must be at least {{ limit }} characters long',
        maxMessage: 'Equipment name cannot be longer than {{ limit }} characters'
    )]    
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['equipment:read'])]
    #[Assert\NotBlank(message: 'Description cannot be blank')]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: 'Description must be at least {{ limit }} characters long',
        maxMessage: 'Description cannot be longer than {{ limit }} characters'
    )]    
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['equipment:read'])]
    #[Assert\NotNull(message: 'Category must be specified')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['equipment:read'])]
    #[Assert\NotNull(message: 'Location must be specified')]
    private ?Location $location = null;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['equipment:read'])]
    #[Assert\NotNull(message: 'Condition must be specified')]
    private ?Condition $eCondition = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'equipment')]
    #[Groups(['equipment:read'])]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getECondition(): ?Condition
    {
        return $this->eCondition;
    }

    public function setECondition(?Condition $eCondition): static
    {
        $this->eCondition = $eCondition;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setEquipment($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEquipment() === $this) {
                $reservation->setEquipment(null);
            }
        }

        return $this;
    }
}
