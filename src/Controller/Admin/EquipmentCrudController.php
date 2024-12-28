<?php

namespace App\Controller\Admin;

use App\Entity\Equipment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EquipmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equipment::class;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Équipement')
            ->setEntityLabelInPlural('Équipements')
            ->setSearchFields(['name', 'description', 'category.name', 'location.aisle', 'eCondition.name'])
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name', 'Nom');
        yield TextEditorField::new('description')->hideOnIndex();
        yield AssociationField::new('category', 'Catégorie')
            ->setFormTypeOption('choice_label', 'name')
            ->formatValue(function ($value, $entity) {
                return $entity->getCategory() ? $entity->getCategory()->getName() : '';
            });
        yield AssociationField::new('location', 'Emplacement')
            ->setFormTypeOption('choice_label', 'aisle')
            ->formatValue(function ($value, $entity) {
                return $entity->getLocation() ?
                    sprintf('%s-%d',
                        $entity->getLocation()->getAisle(),
                            $entity->getLocation()->getShelfNumber()
                    ) : '';
            });
        yield AssociationField::new('eCondition', 'État')
            ->setFormTypeOption('choice_label', 'name')
            ->formatValue(function ($value, $entity) {
                return $entity->getECondition() ? $entity->getECondition()->getName() : '';
            });
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->addCssClass('btn btn-primary');
            });
    }
}