<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Equipment;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReservationRepository $reservationRepository
    ) {}

    public function createReservation(Equipment $equipment, User $user, \DateTime $startDate, \DateTime $endDate): Reservation
    {
        if (!$this->isEquipmentAvailable($equipment, $startDate, $endDate)) {
            throw new \Exception('Equipment not available for these dates');
        }

        $reservation = new Reservation();
        $reservation->setEquipment($equipment);
        $reservation->setUser($user);
        $reservation->setStartDate($startDate);
        $reservation->setEndDate($endDate);
        $reservation->setStatus('pending');

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }

    public function isEquipmentAvailable(Equipment $equipment, \DateTime $startDate, \DateTime $endDate): bool
    {
        $conflictingReservations = $this->reservationRepository->findConflictingReservations(
            $equipment,
            $startDate,
            $endDate
        );
        return count($conflictingReservations) === 0;
    }

    public function updateReservationStatus(Reservation $reservation, string $status): void
    {
        $reservation->setStatus($status);
        $this->entityManager->flush();
    }
}