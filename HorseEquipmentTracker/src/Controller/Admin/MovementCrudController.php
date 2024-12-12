<?php
// src/Controller/Admin/MovementCrudController.php

namespace App\Controller\Admin;

use App\Entity\Movement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class MovementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movement::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('equipment'),
            AssociationField::new('user'),
            DateTimeField::new('startDate'),
            DateTimeField::new('endDate'),
            TextareaField::new('notes'),
        ];
    }
}