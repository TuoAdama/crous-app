<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' =>  [
                    'class' => 'form-control signup-name',
                    'required' => true,
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ]
            ])
            ->add('notifyByNumber', CheckboxType::class, [
                'required' => false,
                'label' => 'form.phone',
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'constraints' => [new NotNull(), new Type('boolean')]
            ])
            ->add('notifyByEmail', CheckboxType::class, [
                'label' => 'form.email',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'constraints' => [new NotNull(), new Type('boolean')]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
