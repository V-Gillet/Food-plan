<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MealSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add(
                'name',
                SearchType::class,
                [
                    'required' => false,
                    'label' => 'Rechercher',
                    'label_attr' => [
                        'class' => 'fs-4 text-primary px-2 m-0'
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
                    'label' => 'Type',
                    'placeholder' => 'Type',
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                    'attr' => [
                        'class' => 'rounded-5 border border-tertiary border-1',
                    ],
                    'required' => false
                ]
            )
            ->add(
                'favourite',
                CheckboxType::class,
                [
                    'label' => 'Favoris',
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
            ->add(
                'origin',
                CountryType::class,
                [
                    'label' => 'Origine',
                    'placeholder' => 'Origine',
                    'attr' => [
                        'class' => 'rounded-5 border border-secondary border-1',
                    ],
                    'label_attr' => [
                        'class' => ' fs-5 mt-3'
                    ],
                    'required' => false
                ]
            );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
