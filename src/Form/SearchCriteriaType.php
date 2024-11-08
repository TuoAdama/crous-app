<?php

namespace App\Form;

use App\Entity\SearchCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class SearchCriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', HiddenType::class, $this->getLocationAttr())
            ->add('address', ChoiceType::class, $this->getAddressAttr([]))
            ->add('type', ChoiceType::class, $this->getTypeLocationAttr())
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
            ->addEventListener(FormEvents::PRE_SET_DATA,[$this, 'onPreSetData'])
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

    public function onPreSetData(PreSetDataEvent $event): void
    {
        /** @var SearchCriteria $criteria */
        $criteria = $event->getData();
        if ($criteria->getId() != null){
            $form = $event->getForm();
            $location = $criteria->getLocation();
            $locationName  = $location['properties']['name'];
            $form->add('address', ChoiceType::class, $this->getAddressAttr([
                $locationName => $locationName,
            ], $locationName));

            $form->add('location', HiddenType::class, $this->getLocationAttr($location))
                ->add('type', ChoiceType::class, $this->getTypeLocationAttr(false));
        }
    }


    private function getAddressAttr(array $choices, ?string $choiceValue = null): array {

        $attr = [
            'mapped' => false,
            'required' => count($choices) == 0,
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
        ];
        if (count($choices) > 0) {
            $attr['choices'] = $choices;
        }
        if ($choiceValue != null){
            $attr['choice_value'] = $choiceValue;
        }

        return $attr;
    }

    private function getLocationAttr(?array $value = null): array {
        $attr = [
            'required' => true,
            'mapped' => false,
            'attr' => [
                'class' => 'location',
            ]
        ];
        if ($value != null){
            $attr['data'] = json_encode($value);
        }
        return $attr;
    }

    private function getTypeLocationAttr(bool $addedDefaultValue = true): array {
        $attr = [
            'required' => true,
            'expanded' => true,
            'multiple' => true,
            'constraints' => [
                new NotBlank(),
                new NotNull(),
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
        ];

        if ($addedDefaultValue){
            $attr['data'] = ['individuel'];
        }


        return $attr;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchCriteria::class,
        ]);
    }
}
