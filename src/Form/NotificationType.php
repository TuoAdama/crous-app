<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('notifyByNumber', CheckboxType::class, [
                'label' => 'form.phone',
                'attr' => [
                    'class' => 'form-check-input',
                ]
            ])
            ->add('notifyByEmail', CheckboxType::class, [
                'label' => 'form.email',
                'attr' => [
                    'class' => 'form-check-input',
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
