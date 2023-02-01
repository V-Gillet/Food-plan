<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class UserInformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('email')
            ->remove('roles')
            ->remove('password')
            ->remove('firstname')
            ->remove('lastname')
            ->add(
                'height',
                NumberType::class,
                [
                    'label' => 'Votre Taille',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => 'fs-5'
                    ],
                ]
            )
            ->add(
                'tempWeight',
                NumberType::class,
                [
                    'label' => 'Votre poids',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => 'fs-5'
                    ],
                ]
            )
            ->add('goal', ChoiceType::class, [
                'choices'  => [
                    'Maitenance' => 'maitenance',
                    'Prise de masse' => 'gain',
                    'Sèche' => 'lean',
                ],
                'label' => 'Votre objectif',
                'label_attr' => [
                    'class' => 'fs-5'
                ],
                'attr' => [
                    'class' => ' border border-primary border-1',
                ],
            ])
            ->add('sexe', ChoiceType::class, [
                'choices'  => [
                    'Homme' => 'male',
                    'Femme' => 'female'
                ],
                'label' => 'Votre sexe',
                'attr' => [
                    'class' => ' border border-primary border-1',
                ],
                'label_attr' => [
                    'class' => 'fs-5'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
