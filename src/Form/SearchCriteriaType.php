<?php

namespace App\Form;

use App\Entity\SearchCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchCriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', HiddenType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'location',
                ]
            ])
            ->add('address', ChoiceType::class, [
                'mapped' => false,
                'required' => true,
                "placeholder" => "Exemple: Rennes, Résidence ou lieu d'études",
                'label' => 'housing.label',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'id' => 'input-location',
                    'class' => 'form-control address-select',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new NotBlank(),
                ],
                'choices'  => [
                    'Individuel' => 'individuel',
                    'Colocation'    => 'colocation',
                    'Couple'    => 'couple',
                ],
                'choice_attr' => function(){
                    return [
                        'class' => 'form-check-input input-type-location'
                    ];
                },
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'data' => ['individuel'],
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'label' => 'Prix maximun',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'html5' => true,
                'attr' => [
                    'min' => 0,
                    'placeholder' => 0,
                    'class' => 'form-control',
                    'id' => 'input-price'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit'])
        ;
    }

    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        $form = $event->getForm();
        $address = $data['address'] ?? null;
        if ($address != null){
            $location = json_decode($data['location'], true);
            $choiceName = $location['properties']['name'];
            $form->add('address', ChoiceType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'housing.label',
                'choices' => [
                    $choiceName => $address,
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'id' => 'input-location',
                    'class' => 'form-control address-select',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
            $event->setData($data);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchCriteria::class,
        ]);
    }
}
