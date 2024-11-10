<?php

namespace App\Entity\Request;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['email'], entityClass: User::class )]
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


    public function confirmEmail()
    {
        
    }
}