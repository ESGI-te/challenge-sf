<?php

namespace App\Controller\BO;

use App\Entity\RecipeSeason;
use App\Form\RecipeSeasonType;
use App\Repository\RecipeSeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('BO/recipe/season')]
class RecipeSeasonController extends AbstractController
{
    #[Route('/', name: 'app_recipe_season_index', methods: ['GET'])]
    public function index(RecipeSeasonRepository $recipeSeasonRepository): Response
    {
        return $this->render('recipe_season/index.html.twig', [
            'recipe_seasons' => $recipeSeasonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_season_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecipeSeasonRepository $recipeSeasonRepository): Response
    {
        $recipeSeason = new RecipeSeason();
        $form = $this->createForm(RecipeSeasonType::class, $recipeSeason);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeSeasonRepository->save($recipeSeason, true);

            return $this->redirectToRoute('app_recipe_season_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe_season/new.html.twig', [
            'recipe_season' => $recipeSeason,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_season_show', methods: ['GET'])]
    public function show(RecipeSeason $recipeSeason): Response
    {
        return $this->render('recipe_season/show.html.twig', [
            'recipe_season' => $recipeSeason,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_season_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecipeSeason $recipeSeason, RecipeSeasonRepository $recipeSeasonRepository): Response
    {
        $form = $this->createForm(RecipeSeasonType::class, $recipeSeason);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeSeasonRepository->save($recipeSeason, true);

            return $this->redirectToRoute('app_recipe_season_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe_season/edit.html.twig', [
            'recipe_season' => $recipeSeason,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_season_delete', methods: ['POST'])]
    public function delete(Request $request, RecipeSeason $recipeSeason, RecipeSeasonRepository $recipeSeasonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipeSeason->getId(), $request->request->get('_token'))) {
            $recipeSeasonRepository->remove($recipeSeason, true);
        }

        return $this->redirectToRoute('app_recipe_season_index', [], Response::HTTP_SEE_OTHER);
    }
}
