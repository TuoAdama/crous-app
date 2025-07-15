<?php

namespace App\Validator;

use App\Enum\TypeLocation;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use UnexpectedValueException;

#[\Attribute]
class IsTypeLocationValidator extends ConstraintValidator
{

    public function __construct(
        private TranslatorInterface $translator,
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsTypeLocation){
            throw new UnexpectedTypeException($value, IsTypeLocation::class);
        }
        if (!is_string($value)){
            throw new UnexpectedValueException($value, 'string');
        }
        if (null == $value){
            return;
        }
        $types = array_values(TypeLocation::getTypesLocation());
        $types = array_map(function($type){
            return strtolower($type);
        }, $types);

        if (in_array($value, $types)){
            return;
        }

        $this->context->buildViolation($this->translator->trans($constraint->message))
            ->addViolation();
    }
}
