<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function listRecipes(Request $request ,EntityManagerInterface $entityManager): Response
    {
        $sort = $request->query->get('sort', 'created_at'); // default sort by created_at
        $recipes = $entityManager->getRepository(Recipe::class)->findBy([], ['createdAt' => 'DESC']);
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes,
            'sort' => $sort,
        ]);
    }
}
