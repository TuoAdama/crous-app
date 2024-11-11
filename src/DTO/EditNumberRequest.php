<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EditNumberRequest
{
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/0[1-9]{1}[0-9]{8}/')]
    public string $number;
    #[Assert\NotBlank]
    public string $token;
}