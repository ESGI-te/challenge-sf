<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface|string $id;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?RecipeMood $mood = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?RecipeCourse $meal_course = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?RecipeDuration $duration = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?RecipeSeason $season = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?RecipeMealtime $mealtime = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recipes')]
    private Collection $ingredients;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToMany(targetEntity: Favorite::class, inversedBy: 'recipes')]
    private Collection $favorites;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMood(): ?RecipeMood
    {
        return $this->mood;
    }

    public function setMood(?RecipeMood $mood): self
    {
        $this->mood = $mood;

        return $this;
    }

    public function getMealCourse(): ?RecipeCourse
    {
        return $this->meal_course;
    }

    public function setMealCourse(?RecipeCourse $meal_course): self
    {
        $this->meal_course = $meal_course;

        return $this;
    }

    public function getDuration(): ?RecipeDuration
    {
        return $this->duration;
    }

    public function setDuration(?RecipeDuration $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSeason(): ?RecipeSeason
    {
        return $this->season;
    }

    public function setSeason(?RecipeSeason $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getMealtime(): ?RecipeMealtime
    {
        return $this->mealtime;
    }

    public function setMealtime(?RecipeMealtime $mealtime): self
    {
        $this->mealtime = $mealtime;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setRecipe($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getRecipe() === $this) {
                $like->setRecipe(null);
            }
        }

        return $this;
    }
}
