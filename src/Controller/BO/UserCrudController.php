<?php

namespace App\Controller\BO;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setEntityLabelInPlural("Utilisateurs");
    }



    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('firstname')->setLabel('Prénom');
        yield TextField::new('lastname')->setLabel('Nom');
        yield EmailField::new('email')->setLabel('Email');
        yield TextField::new('username')->setLabel('Username');
        yield ChoiceField::new('roles')
            ->setLabel('Rôles')
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_USER' => 'warning',
                'IS_FULLY_AUTHENTICATED' => 'success',
                'ROLE_ADMIN' => 'success',
            ])
            ->setChoices([
                'Authenticated' => 'IS_FULLY_AUTHENTICATED',
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN'
            ]);
        yield DateField::new('createdAt')
            ->setLabel('Créé à')
            ->onlyOnIndex();
    }

}
