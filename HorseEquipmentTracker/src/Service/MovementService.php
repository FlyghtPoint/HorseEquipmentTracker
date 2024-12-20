<?php

namespace App\Service;

use App\Entity\Movement;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class MovementService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {}

    public function createMovement(Reservation $reservation, string $type, \DateTime $date): Movement
    {
        $this->logger->debug('Creating movement', [
            'reservation_id' => $reservation->getId(),
            'type' => $type,
            'date' => $date->format('Y-m-d H:i:s')
        ]);

        $movement = new Movement();
        $movement->setReservation($reservation);
        $movement->setType($type);
        $movement->setDate($date);

        $this->entityManager->persist($movement);
        $this->entityManager->flush();

        $this->logger->debug('Movement created', [
            'movement_id' => $movement->getId(),
            'reservation_id' => $reservation->getId()
        ]);

        return $movement;
    }
}