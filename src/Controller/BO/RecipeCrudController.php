<?php

namespace App\Controller\BO;

use App\Entity\Recipe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setEntityLabelInPlural("Recettes");
    }


    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('duration')->setLabel('La durée');
        yield AssociationField::new('difficulty')->setLabel('La difficulté');
        yield AssociationField::new('user_id')->setLabel('Email d\'utilisateur');
        yield TextField::new('content')->setLabel('Contenu');
        yield NumberField::new('nb_people')->setLabel('Capacité d\'accueil');
        yield DateField::new('createdAt')->setLabel('Créé à');

    }

}
