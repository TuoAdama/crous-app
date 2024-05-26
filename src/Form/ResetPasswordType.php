<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control py-3',
                    'placeholder' => 'Ex: email@domain.com'
                ]
            ])
            ->add('send', SubmitType::class, [
                'label' => "reset",
                'attr' => [
                    'class' => 'btn btn-primary mt-3 text-white'
                ]
            ]);
    }
}
