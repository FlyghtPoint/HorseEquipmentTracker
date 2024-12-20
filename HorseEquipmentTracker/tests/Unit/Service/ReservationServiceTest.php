<?php
namespace App\Tests\Unit\Service;

use App\Entity\Equipment;
use App\Entity\Reservation;
use App\Entity\User;
use App\Service\ReservationService;
use App\Service\MovementService;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

class ReservationServiceTest extends TestCase
{
    /** @var EntityManagerInterface&MockObject */
    private EntityManagerInterface|MockObject $entityManager;

    /** @var ReservationRepository&MockObject */
    private ReservationRepository|MockObject $reservationRepository;
    
    /** @var MovementService&MockObject */
    private MovementService|MockObject $movementService;
    
    /** @var LoggerInterface&MockObject */
    private LoggerInterface|MockObject $logger;
    
    private ReservationService $reservationService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->reservationRepository = $this->createMock(ReservationRepository::class);
        $this->movementService = $this->createMock(MovementService::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->reservationService = new ReservationService(
            $this->entityManager,
            $this->reservationRepository,
            $this->movementService,
            $this->logger
        );
    }

    public function testCreateReservationWithMovements(): void
    {
        // Arrange
        $equipment = new Equipment();
        $user = new User();
        $startDate = new \DateTime();
        $endDate = new \DateTime('+1 day');

        $this->reservationRepository
            ->expects($this->once())
            ->method('findConflictingReservations')
            ->willReturn([]);

        $this->movementService
            ->expects($this->exactly(2))
            ->method('createMovement');

        // Act
        $reservation = $this->reservationService->createReservation(
            $equipment,
            $user,
            $startDate,
            $endDate
        );

        // Assert
        $this->assertEquals('pending', $reservation->getStatus());
        $this->assertEquals($equipment, $reservation->getEquipment());
        $this->assertEquals($user, $reservation->getUser());
    }

    public function testRejectConflictingReservation(): void
    {
        // Arrange
        $equipment = new Equipment();
        $user = new User();
        $startDate = new \DateTime();
        $endDate = new \DateTime('+1 day');

        $this->reservationRepository
            ->expects($this->once())
            ->method('findConflictingReservations')
            ->willReturn([new Reservation()]);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Equipment not available for these dates');

        // Act
        $this->reservationService->createReservation(
            $equipment,
            $user,
            $startDate,
            $endDate
        );
    }

    // public function testRejectInvalidDates(): void
    // {
    //     // Arrange
    //     $equipment = new Equipment();
    //     $user = new User();
    //     $startDate = new \DateTime('+1 days');
    //     $endDate = new \DateTime('+2 day');

    //     // Assert
    //     $this->expectException(\Exception::class);
    //     $this->expectExceptionMessage('End date must be after start date');

    //     // Act
    //     $this->reservationService->createReservation(
    //         $equipment,
    //         $user,
    //         $startDate,
    //         $endDate
    //     );
    // }
}