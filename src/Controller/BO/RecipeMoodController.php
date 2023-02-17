<?php

namespace App\Controller\BO;

use App\Entity\RecipeMood;
use App\Form\RecipeMoodType;
use App\Repository\RecipeMoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('BO/recipe/mood')]
class RecipeMoodController extends AbstractController
{
    #[Route('/', name: 'app_recipe_mood_index', methods: ['GET'])]
    public function index(RecipeMoodRepository $recipeMoodRepository): Response
    {
        return $this->render('recipe_mood/index.html.twig', [
            'recipe_moods' => $recipeMoodRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_mood_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecipeMoodRepository $recipeMoodRepository): Response
    {
        $recipeMood = new RecipeMood();
        $form = $this->createForm(RecipeMoodType::class, $recipeMood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeMoodRepository->save($recipeMood, true);

            return $this->redirectToRoute('app_recipe_mood_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe_mood/new.html.twig', [
            'recipe_mood' => $recipeMood,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_mood_show', methods: ['GET'])]
    public function show(RecipeMood $recipeMood): Response
    {
        return $this->render('recipe_mood/show.html.twig', [
            'recipe_mood' => $recipeMood,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_mood_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecipeMood $recipeMood, RecipeMoodRepository $recipeMoodRepository): Response
    {
        $form = $this->createForm(RecipeMoodType::class, $recipeMood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeMoodRepository->save($recipeMood, true);

            return $this->redirectToRoute('app_recipe_mood_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe_mood/edit.html.twig', [
            'recipe_mood' => $recipeMood,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_mood_delete', methods: ['POST'])]
    public function delete(Request $request, RecipeMood $recipeMood, RecipeMoodRepository $recipeMoodRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipeMood->getId(), $request->request->get('_token'))) {
            $recipeMoodRepository->remove($recipeMood, true);
        }

        return $this->redirectToRoute('app_recipe_mood_index', [], Response::HTTP_SEE_OTHER);
    }
}
