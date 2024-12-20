<?php
// src/Controller/Admin/ReservationCrudController.php
namespace App\Controller\Admin;

use App\Entity\Reservation;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Psr\Log\LoggerInterface;

class ReservationCrudController extends AbstractCrudController
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }
}