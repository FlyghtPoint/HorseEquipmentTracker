<?php

namespace App\Repository;

use App\Entity\Equipment;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findConflictingReservations(Equipment $equipment, \DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.equipment = :equipment')
            ->andWhere('r.status != :cancelledStatus')
            ->andWhere(
                '(r.startDate BETWEEN :start AND :end) OR 
                 (r.endDate BETWEEN :start AND :end) OR
                 (r.startDate <= :start AND r.endDate >= :end)'
            )
            ->setParameters(new \Doctrine\Common\Collections\ArrayCollection([
                'equipment' => $equipment,
                'cancelledStatus' => 'cancelled',
                'start' => $startDate,
                'end' => $endDate
            ]))
            ->getQuery()
            ->getResult();
    }
    
    public function findUpcomingReservations(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.user = :user')
            ->andWhere('r.endDate >= :today')
            ->andWhere('r.status != :cancelledStatus')
            ->setParameters(new \Doctrine\Common\Collections\ArrayCollection([
                'user' => $user,
                'today' => new \DateTime(),
                'cancelledStatus' => 'cancelled'
            ]))
            ->orderBy('r.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }    
}
