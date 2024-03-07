<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class VerificationNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('code', NumberType::class, [
            'attr' => [
                'class' => 'form-control login-email',
                'placeholder' => 'code de vérification',
            ],
            'label' => false,
            'constraints' => [
                new NotBlank(),
            ]
        ]);
    }
}