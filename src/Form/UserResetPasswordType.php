<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                "options" => [
                    'attr' => [
                        'class' => 'form-control signup-password mb-2',
                        'required' => true,
                    ],
                ],
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control signup-password mb-3 mt-2',
                        'placeholder' => 'input.password.new'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control signup-password mb-3 mt-2',
                        'placeholder' => "input.password.confirm.label"
                    ]
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => "reset",
                'attr' => [
                    'class' => 'btn btn-primary mt-3 text-white'
                ]
            ]);
        ;
    }
}
