<?php

namespace App\Form;

use App\Entity\Meal;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('calories')
            ->remove('poster')
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom du repas',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Recette ou description',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                ]
            )
            ->add(
                'origin',
                CountryType::class,
                [
                    'label' => 'Pays d\'origine',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                ]
            )
            ->add(
                'lipid',
                IntegerType::class,
                [
                    'label' => 'Lipides du repas',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                ]
            )
            ->add(
                'carb',
                IntegerType::class,
                [
                    'label' => 'Glucides du repas',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                ]
            )
            ->add(
                'protein',
                IntegerType::class,
                [
                    'label' => 'Protéines du repas',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                ]
            )
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices'  => [
                        'Petit-déjeuner' => 'Petit-déjeuner',
                        'Déjeuner' => 'Déjeuner',
                        'En-cas' => 'En-cas',
                        'Diner' => 'Diner',
                    ],
                    'label' => 'Type de repas',
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                ]
            )
            ->add('isRecipe', CheckboxType::class, [
                'label' =>
                'Partager',
                'label_attr' => [
                    'class' => 'form-check-label',
                    'for' => 'flexSwitchCheckDefault'
                ],
                'attr' => [
                    'class' => 'form-check-input',
                    'role' => 'switch',
                    'id' => 'flexSwitchCheckDefault'
                ],
                'required' => false,
            ])
            ->add(
                'isFavourite',
                CheckboxType::class,
                [
                    'label' => 'Ajouter aux favoris',
                    'label_attr' => [
                        'class' => 'form-check-label',
                        'for' => 'flexSwitchCheckDefault'
                    ],
                    'attr' => [
                        'class' => 'form-check-input',
                        'role' => 'switch',
                        'id' => 'flexSwitchCheckDefault'
                    ],
                    'required' => false,
                ]
            )
            ->add('posterFile', VichFileType::class, [
                'label' => 'Photo du repas',
                'label_attr' => [
                    'class' => ' fs-5 mt-3 font-title'
                ],
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_uri' => true, // not mandatory, default is true
            ])
            ->add(
                'date',
                DateType::class,
                [
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                    'widget' => 'single_text',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
        ]);
    }
}
