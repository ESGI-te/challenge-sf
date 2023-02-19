<?php

namespace App\Service;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RecipeService
{
    public function __construct(RecipeGenerationService $recipeGenerationService, EntityManagerInterface $em, Security $security) {
        $this->generationService = $recipeGenerationService;
        $this->em = $em;
        $this->security = $security;
    }

    public function create(Recipe $recipe) {
        $content = $this->generationService->generateContent($recipe);
        $title = $this->generationService->generateTitle($recipe->getIngredients()->toArray());
        $image = $this->generationService->generateImage($recipe->getIngredients()->toArray(), $title);
        $date = new \DateTimeImmutable('now');

        $recipe->setContent($content);
        $recipe->setTitle($title);
        $recipe->setImage($image);
        $recipe->setUserId($this->security->getUser());
        $recipe->setCreatedAt($date);

        $this->em->persist($recipe);
        $this->em->flush();

        return $recipe;
    }
}