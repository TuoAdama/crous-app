<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' =>  [
                    'class' => 'form-control signup-name',
                    'required' => true,
                ],
                'label' => 'Nom:',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control email signup-email',
                    'required' => true,
                ],
                'label' => 'E-mail:',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                "options" => [
                    'attr' => [
                        'class' => 'form-control signup-password mb-2',
                        'required' => true,
                    ],
                ],
                'invalid_message' => 'input.password.invalid.message',
                'first_options'  => ['label' => 'input.password.label'],
                'second_options' => ['label' => 'input.password.confirm.label'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn app-btn-primary w-100 theme-btn mx-auto'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
