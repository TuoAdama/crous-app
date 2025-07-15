<?php

namespace App\Validator;


use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsExtent extends Constraint
{

    public string $message = "constraint.message.extent";

    #[HasNamedArguments]
    public function __construct(
        ?array $groups = [],
        mixed $payload = null,
    )
    {
        parent::__construct([], $groups, $payload);
    }
}
