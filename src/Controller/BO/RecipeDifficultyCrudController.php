<?php

namespace App\Controller\BO;

use App\Entity\RecipeDifficulty;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecipeDifficultyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeDifficulty::class;
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
