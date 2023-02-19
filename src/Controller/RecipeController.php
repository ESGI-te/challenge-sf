<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Service\RecipeGenerationService;
use App\Service\RecipeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/recipes', name: 'recipe_')]
class RecipeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'list')]
    public function list(Request $request): Response
    {
        $sort = $request->query->get('sort', 'created_at'); // default sort by created_at
        $recipes = $this->entityManager->getRepository(Recipe::class)->findBy([], ['createdAt' => 'DESC']);
        return $this->render('recipe/list.html.twig', [
            'recipes' => $recipes,
            'sort' => $sort,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show($id): Response
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('Recipe not found');
        }

        return $this->render('recipe/index.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/generate', name: 'generate', priority: 2)]
    public function generate(Request $request, RecipeService $recipeService): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $generatedRecipe = $recipeService->create($recipe);
            return $this->redirectToRoute('recipe_show', ['id' => $generatedRecipe->getId()]);
        }

        return $this->render('recipe/generate.html.twig', [
            'form' => $form,
        ]);
    }
}
