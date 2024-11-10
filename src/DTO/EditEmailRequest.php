<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EditEmailRequest
{
    #[Assert\Email]
    public string $email;
    #[Assert\Email]
    #[Assert\Expression(
        'value === this.email',
        message: 'emails are not identical',
    )]
    public string $confirmEmail;

    #[Assert\NotBlank]
    public string $password;

    #[Assert\NotBlank]
    public string $token;
}