<?php

namespace App\Repository;

use App\Entity\Equipment;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Types;

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
        $qb = $this->createQueryBuilder('r')
            ->where('r.equipment = :equipment')
            ->andWhere('r.status != :cancelledStatus')
            ->andWhere(
                '(r.start_date BETWEEN :start AND :end) OR 
                (r.end_date BETWEEN :start AND :end) OR
                (r.start_date <= :start AND r.end_date >= :end)'
            );

        $qb->setParameter('equipment', $equipment, Types::JSON)
        ->setParameter('cancelledStatus', 'cancelled', Types::STRING)
        ->setParameter('start', $startDate, Types::DATETIME_MUTABLE)
        ->setParameter('end', $endDate, Types::DATETIME_MUTABLE);

        return $qb->getQuery()->getResult();        
    }
    
    public function findUpcomingReservations(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.user = :user')
            ->andWhere('r.end_date >= :today')
            ->andWhere('r.status != :cancelledStatus')
            ->setParameters(new \Doctrine\Common\Collections\ArrayCollection([
                'user' => $user,
                'today' => new \DateTime(),
                'cancelledStatus' => 'cancelled'
            ]))
            ->orderBy('r.start_date', 'ASC')
            ->getQuery()
            ->getResult();
    }    
}
