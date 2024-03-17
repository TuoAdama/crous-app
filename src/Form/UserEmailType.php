<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
            'label' => 'Email:',
            'attr' => [
                'class' => 'form-control my-2'
            ]
        ])->add('submit', SubmitType::class, [
            'label' => 'Mettre à jour',
            'attr' => [
                'class' => 'btn app-btn-primary',
//                'disabled' => 'true'
            ]
        ]);
    }
}