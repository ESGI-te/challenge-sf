<?php

namespace App\Controller\BO;

use App\Entity\RecipeMealtime;
use App\Form\RecipeMealtimeType;
use App\Repository\RecipeMealtimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('BO/recipe/mealtime')]
class RecipeMealtimeController extends AbstractController
{
    #[Route('/', name: 'app_recipe_mealtime_index', methods: ['GET'])]
    public function index(RecipeMealtimeRepository $recipeMealtimeRepository): Response
    {
        return $this->render('recipe_mealtime/index.html.twig', [
            'recipe_mealtimes' => $recipeMealtimeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_mealtime_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecipeMealtimeRepository $recipeMealtimeRepository): Response
    {
        $recipeMealtime = new RecipeMealtime();
        $form = $this->createForm(RecipeMealtimeType::class, $recipeMealtime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeMealtimeRepository->save($recipeMealtime, true);

            return $this->redirectToRoute('app_recipe_mealtime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe_mealtime/new.html.twig', [
            'recipe_mealtime' => $recipeMealtime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_mealtime_show', methods: ['GET'])]
    public function show(RecipeMealtime $recipeMealtime): Response
    {
        return $this->render('recipe_mealtime/show.html.twig', [
            'recipe_mealtime' => $recipeMealtime,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_mealtime_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecipeMealtime $recipeMealtime, RecipeMealtimeRepository $recipeMealtimeRepository): Response
    {
        $form = $this->createForm(RecipeMealtimeType::class, $recipeMealtime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeMealtimeRepository->save($recipeMealtime, true);

            return $this->redirectToRoute('app_recipe_mealtime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe_mealtime/edit.html.twig', [
            'recipe_mealtime' => $recipeMealtime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_mealtime_delete', methods: ['POST'])]
    public function delete(Request $request, RecipeMealtime $recipeMealtime, RecipeMealtimeRepository $recipeMealtimeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipeMealtime->getId(), $request->request->get('_token'))) {
            $recipeMealtimeRepository->remove($recipeMealtime, true);
        }

        return $this->redirectToRoute('app_recipe_mealtime_index', [], Response::HTTP_SEE_OTHER);
    }
}
