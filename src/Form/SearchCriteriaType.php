<?php

namespace App\Form;

use App\Entity\SearchCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', ChoiceType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'id' => 'location',
                    'class' => 'form-control',
                ]
            ])
            ->add('type', CollectionType::class, [
                'entry_type'   => CheckboxType::class,
                'allow_add' => true,
                'entry_options'  => [
                    'choices'  => [
                        'Nashville' => 'nashville',
                        'Paris'     => 'paris',
                        'Berlin'    => 'berlin',
                        'London'    => 'london',
                    ],
                    'attr' => [
                        'class' => 'form-check-input',
                    ]
                ],
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'label' => 'Prix maximun',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'min' => 0,
                    'placeholder' => 0,
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchCriteria::class,
        ]);
    }
}
