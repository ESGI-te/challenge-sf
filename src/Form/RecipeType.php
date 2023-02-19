<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientType;
use App\Entity\Recipe;
use App\Entity\RecipeDifficulty;
use App\Entity\RecipeDuration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RecipeType extends AbstractType
{
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('difficulty', EntityType::class, [
                'class' => RecipeDifficulty::class,
                'choice_label' => 'name',
            ])            ->add('nb_people')
            ->add('duration', EntityType::class, [
                'class' => RecipeDuration::class,
                'choice_label' => 'name',
            ])
            ->add('nb_people', RangeType::class, [
                'label' => 'Nombre de personnes',
                'attr' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ]
            ]);
            $this->addIngredientsOfTypeField($builder);
            $this->getIngredients($builder);
    }

    public function addIngredientsOfTypeField(FormBuilderInterface $builder)
    {
        $ingredientTypes = $this->em->getRepository(IngredientType::class)->findAll();

        foreach ($ingredientTypes as $type) {
            $builder->add($type->getName(), EntityType::class, [
                'class' => Ingredient::class,
                'choices' => $type->getIngredients(),
                'multiple' => true,
                'expanded' => false,
                'label' => $type->getName(),
                'choice_label' => 'name',
                'mapped' => false,
                'required' => false
            ]);
        }
    }

    public function getIngredients(FormBuilderInterface $builder) {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $recipe = $event->getData();
            $form = $event->getForm();

            // Initialize ingredients prop
            $ingredients = $recipe->getIngredients() ?? new ArrayCollection();

            // Iterate on all additional fields
            foreach ($form->all() as $name => $field) {
                // Check if field is ingredient type field
                $type = $this->em->getRepository(IngredientType::class)->findOneBy(['name' => $name]);
                if ($type !== null) {
                    $selectedIngredients = $field->getData(); // Get field value(s)
                    foreach ($selectedIngredients as $ingredient) {
                        $ingredients->add($ingredient); // Add each ingredient to ingredients prop
                    }
                }
            }
            foreach ($ingredients as $ingredient) {
                $recipe->addIngredient($ingredient);
            }
            $event->setData($recipe);
        });
    }

    public function validateIngredients(Recipe $recipe, ExecutionContextInterface $context): void
    {
        $ingredientsSelected = count($recipe->getIngredients()->toArray());

        if ($ingredientsSelected === 0) {
            $context
                ->buildViolation('Vous devez sélectionner au moins un ingrédient.')
                ->atPath('ingredients')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'allow_extra_fields' => true,
            'constraints' => [
                new Callback([$this, 'validateIngredients']),
            ],
        ]);
    }
}
