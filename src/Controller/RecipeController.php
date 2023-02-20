<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Service\RecipeService;
use App\Form\CommentType;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/recipes', name: 'recipe_')]
class RecipeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CommentService $commentService;

    public function __construct(EntityManagerInterface $entityManager, CommentService $commentService)
    {
        $this->entityManager = $entityManager;
        $this->commentService = $commentService;
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
    public function show(Recipe $recipe, Request $request): Response
    {
        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $content = $commentForm->get('content')->getData();
            $this->commentService->add($recipe, $content);
            $this->addFlash('success', 'Comment added successfully!');
        }

        return $this->render('recipe/index.html.twig', [
            'recipe' => $recipe,
            'comment_form' => $commentForm->createView(),
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

    #[Route('/{id}/comment/delete', name: 'comment_delete', methods: ['DELETE'])]
    public function commentDelete(Request $request, Recipe $recipe): Response
    {
        $commentId = $request->query->get('commentId');
        if ($this->isCsrfTokenValid('delete'.$commentId, $request->request->get('_token'))) {
            $this->commentService->delete($commentId);
            $this->addFlash('success', 'Comment deleted successfully!');
        }
        return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
    }
}
