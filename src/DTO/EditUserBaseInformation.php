<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EditUserBaseInformation
{
    #[Assert\NotBlank]
    public string $username;
    #[Assert\NotBlank]
    public string $notifyByEmail;
    #[Assert\NotBlank]
    public string $notifyByNumber;
    #[Assert\NotBlank]
    public string $_token;
}