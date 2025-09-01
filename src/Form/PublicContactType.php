<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublicContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'class' => 'form-control mb-3 py-3',
                    'placeholder' => 'Votre nom complet'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr' => [
                    'class' => 'form-control mb-3 py-3',
                    'placeholder' => 'votre@email.com'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre email'
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir un email valide'
                    ])
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
                'attr' => [
                    'class' => 'form-control mb-3 py-3',
                    'placeholder' => 'Ex: Question sur un logement, Problème technique...'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un sujet'
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [
                    'class' => 'form-control h-100',
                    'rows' => 6,
                    'placeholder' => 'Décrivez votre question ou votre problème en détail...'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre message'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer le message',
                'attr' => [
                    'class' => 'btn-modern w-full'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
