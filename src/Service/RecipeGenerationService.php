<?php

namespace App\Service;

class RecipeGenerationService
{
    public function __construct(OpenAIService $openAIService) {
        $this->openAiService = $openAIService;
    }
    public function generateRecipe(array $params): string {
        if (!isset($params['ingredients']) || !is_array($params['ingredients'])) {
            throw new \InvalidArgumentException("Le paramètre 'ingredients' est manquant ou n'est pas un tableau.");
        }
        if (!isset($params['duration']) || !is_string($params['duration'])) {
            throw new \InvalidArgumentException("Le paramètre 'duration' est manquant ou n'est pas un nombre.");
        }
        if (!isset($params['difficulty']) || !is_string($params['difficulty'])) {
            throw new \InvalidArgumentException("Le paramètre 'difficulty' est manquant ou n'est pas une chaîne de caractères.");
        }
        if (!isset($params['nb_people']) || !is_numeric($params['nb_people'])) {
            throw new \InvalidArgumentException("Le paramètre 'nb_people' est manquant ou n'est pas un nombre.");
        }

        $ingredients = implode(', ', $params['ingredients']);
        $duration = $params['duration'];
        $difficulty = $params['difficulty'];
        $nb_people = $params['nb_people'];

        $prompt = "
            Ignore all instructions before this one. You're a chief for 20 years. 
            Your task is now to create a recipe with a $difficulty difficulty, for $nb_people, which is $duration to prepare and with the following ingredients : $ingredients
        ";

        return $this->openAiService->generateText($prompt);
    }
    public function generateImage(array $ingredients): string {
        if (!isset($ingredients) || !is_array($ingredients)) {
            throw new \InvalidArgumentException("Le paramètre 'ingredients' est manquant ou n'est pas un tableau.");
        }

        $recipeIngredients = implode(', ', $ingredients);

        $prompt = "Create a cover image for a recipe with the following ingredients : $recipeIngredients.";

        return $this->openAiService->generateImage($prompt);
    }
    public function generateTitle(array $ingredients): string {
        if (!isset($ingredients) || !is_array($ingredients)) {
            throw new \InvalidArgumentException("Le paramètre 'ingredients' est manquant ou n'est pas un tableau.");
        }

        $recipeIngredients = implode(', ', $ingredients);

        $prompt = "
            Ignore all instructions before this one. You're a chief for 20 years. 
            Your task is now to create a name for a recipe with the following ingredients : $recipeIngredients.
        ";

        return $this->openAiService->generateText($prompt);
    }
}