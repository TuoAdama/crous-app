<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FranceNumberType extends AbstractType
{
    CONST OPTIONS = [
        'attr' => [
            'class' => 'form-control',
            'required' => false,
            'placeholder' => '0XXXXXXXXX',
            'pattern' => '0[1-9]{1}[0-9]{8}'
        ],
        'label' => false,
    ];
    public function getParent(): string
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ] + self::OPTIONS);
    }
}