<?php

namespace App\Controller\BO;

use App\Entity\Ingredient;
use App\Entity\IngredientType;
use App\Entity\Recipe;
use App\Entity\RecipeDifficulty;
use App\Entity\RecipeDuration;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/BO', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App OpenAI');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::subMenu('Catégorie d\'ingrédients', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Ingrédients', 'fas fa-newspaper', Ingredient::class),
            MenuItem::linkToCrud(' Type d\'Ingrédient', 'fas fa-newspaper', IngredientType::class)
        ]);

        yield MenuItem::subMenu('Catégorie de recettes', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Recettes', 'fas fa-newspaper', Recipe::class),
            MenuItem::linkToCrud('Difficulté de la recette', 'fas fa-newspaper', RecipeDifficulty::class),
            MenuItem::linkToCrud('Durée de la recette', 'fas fa-newspaper', RecipeDuration::class),
        ]);

    }
}
