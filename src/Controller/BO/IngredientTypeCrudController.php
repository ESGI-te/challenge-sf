<?php

namespace App\Controller\BO;

use App\Entity\IngredientType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IngredientTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IngredientType::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
