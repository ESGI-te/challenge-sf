<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\IngredientType;
use App\Entity\Recipe;
use App\Entity\RecipeDifficulty;
use App\Entity\RecipeDuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'mapped' => true,
                'property_path' => 'ingredients',
            ]);
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'allow_extra_fields' => true,
        ]);
    }
}
