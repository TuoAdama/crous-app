<?php

namespace App\Entity\Request;

use Symfony\Component\Validator\Constraints as Assert;

class EditEmailRequest
{
    #[Assert\Email]
    public string $email;
    #[Assert\Email]
    public string $confirmEmail;
    #[Assert\NotBlank]
    public string $password;
    #[Assert\NotBlank]
    public string $token;
}