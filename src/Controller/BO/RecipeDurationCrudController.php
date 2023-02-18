<?php

namespace App\Controller\BO;

use App\Entity\RecipeDuration;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecipeDurationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeDuration::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setEntityLabelInSingular("une durée")
            ->setEntityLabelInPlural("Durée de la recette");
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name')->setLabel('La durée');
    }

}
