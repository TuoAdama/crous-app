<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    /**
     * @param User $user
     * @return void
     */
    function save(User $user): void
    {
        $passwordHashed = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($passwordHashed);
        $currentTime = new DateTimeImmutable();
        $user->setUpdatedAt($currentTime)
            ->setCreatedAt($currentTime);
        $this->userRepository->save($user);
    }
}