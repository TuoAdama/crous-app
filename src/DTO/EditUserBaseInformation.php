<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EditUserBaseInformation
{
    #[Assert\NotBlank]
    public string $username;
    #[Assert\NotBlank]
    #[Assert\Type('boolean')]
    public string $notifyByEmail;
    #[Assert\NotBlank]
    #[Assert\Type('boolean')]
    public string $notifyByNumber;

    #[Assert\NotBlank]
    public string $token;
}