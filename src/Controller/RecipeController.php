<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RecipeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/recipes/{id}', name: 'recipe_show')]
    public function index($id): Response
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        return $this->render('recipe/index.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recipes', name: 'recipe_list')]
    public function listRecipes(Request $request): Response
    {
        $sort = $request->query->get('sort', 'created_at'); // default sort by created_at
        $recipes = $this->entityManager->getRepository(Recipe::class)->findBy([], ['createdAt' => 'DESC']);
        return $this->render('recipe/list.html.twig', [
            'recipes' => $recipes,
            'sort' => $sort,
        ]);
    }
}
