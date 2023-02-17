<?php

namespace App\Controller\BO;

use App\Entity\RecipeCourse;
use App\Form\RecipeCourseType;
use App\Repository\RecipeCourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('BO/recipe/course')]
class RecipeCourseController extends AbstractController
{
    #[Route('/', name: 'app_recipe_course_index', methods: ['GET'])]
    public function index(RecipeCourseRepository $recipeCourseRepository): Response
    {
        return $this->render('recipe_course/index.html.twig', [
            'recipe_courses' => $recipeCourseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecipeCourseRepository $recipeCourseRepository): Response
    {
        $recipeCourse = new RecipeCourse();
        $form = $this->createForm(RecipeCourseType::class, $recipeCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeCourseRepository->save($recipeCourse, true);

            return $this->redirectToRoute('app_recipe_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe_course/new.html.twig', [
            'recipe_course' => $recipeCourse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_course_show', methods: ['GET'])]
    public function show(RecipeCourse $recipeCourse): Response
    {
        return $this->render('recipe_course/show.html.twig', [
            'recipe_course' => $recipeCourse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecipeCourse $recipeCourse, RecipeCourseRepository $recipeCourseRepository): Response
    {
        $form = $this->createForm(RecipeCourseType::class, $recipeCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipeCourseRepository->save($recipeCourse, true);

            return $this->redirectToRoute('app_recipe_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recipe_course/edit.html.twig', [
            'recipe_course' => $recipeCourse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_course_delete', methods: ['POST'])]
    public function delete(Request $request, RecipeCourse $recipeCourse, RecipeCourseRepository $recipeCourseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipeCourse->getId(), $request->request->get('_token'))) {
            $recipeCourseRepository->remove($recipeCourse, true);
        }

        return $this->redirectToRoute('app_recipe_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
