<?php

namespace App\Controller\BO;

use App\Entity\Ingredient;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class IngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setEntityLabelInSingular("d'ingrédient")
            ->setEntityLabelInPlural("Ingrédients");
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name')->setLabel('Nom d\'ingrédient');
        yield AssociationField::new('type')->setLabel('Type d\'ingrédient');
    }

}
