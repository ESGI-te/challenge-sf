<?php

namespace App\Controller\BO;

use App\Entity\IngredientType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class IngredientTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IngredientType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setEntityLabelInSingular("type d'Ingrédient")
            ->setEntityLabelInPlural("Types d'ingrédients");
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name')->setLabel('Type d\'ingrédient');
    }

}
