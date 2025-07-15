<?php

namespace App\Validator;


use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UnexpectedValueException;

class ExtentValidator extends ConstraintValidator
{

    public string $message = "constraint.message.extent";

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsExtent){
            throw new UnexpectedTypeException($constraint, IsExtent::class);
        }
        if (null === $value){
            return;
        }
        if (!is_string($value)){
            throw new UnexpectedValueException($value, 'string');
        }
        $extent = explode(',', $value);

        if (count($extent) === 4){
            $notNumberItems = array_filter($extent, function($item){
                return is_numeric($item);
            });
            if (count($notNumberItems) == 0){
                return;
            }
        }
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
