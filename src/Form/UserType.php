<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
                    'placeholder' => 'Nom et prénom'
                ],
                'label' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control email signup-email',
                    'required' => true,
                    'placeholder' => 'email'
                ],
                'label' => false,
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control signup-password',
                    'required' => true,
                    'placeholder' => "mot de passe"
                ],
                'label' => false,
            ])
            ->add('Enregistrer', SubmitType::class, [
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
