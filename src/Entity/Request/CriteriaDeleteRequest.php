<?php

namespace App\Entity\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CriteriaDeleteRequest
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[Assert\EqualTo("DELETE")]
        public readonly string $method,

        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $id,

        #[Assert\NotNull]
        #[Assert\NotBlank]
        public readonly string $token,
    )
    {
    }
}
