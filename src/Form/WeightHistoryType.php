<?php

namespace App\Form;

use App\Entity\WeightHistory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class WeightHistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('date')
            ->remove('user')
            ->add(
                'weight',
                NumberType::class,
                [
                    'label' => 'Ajouter mon poids du jour',
                    'attr' => [
                        'class' => ' border border-primary border-1',
                    ],
                    'label_attr' => [
                        'class' => 'fs-5 mt-3 text-center'
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WeightHistory::class,
        ]);
    }
}
