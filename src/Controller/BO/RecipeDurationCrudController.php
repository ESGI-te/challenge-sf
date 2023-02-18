<?php

namespace App\Controller\BO;

use App\Entity\RecipeDuration;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipeDurationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeDuration::class;
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
