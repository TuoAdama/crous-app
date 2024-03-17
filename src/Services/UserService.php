<?php

namespace App\Services;

use App\Entity\User;
use App\Exceptions\NumberCodeException;
use App\Repository\UserRepository;
use App\Services\Token\SmsTokenValidator;
use App\Services\Token\TokenGenerator;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly CodeGenerator $codeGenerator,
        private readonly SmsInterface $smsSender,
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenGenerator $tokenGenerator,
        private readonly ExpirationService $expirationService,
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


    /**
     * @throws Exception
     */
    public function verifyNumber(User $user): void
    {
        $temporaryCode = $this->codeGenerator->generate();
        $user->setTemporaryNumberCode($temporaryCode);
        $message = "Votre code de vérification est: ".$temporaryCode;
        $expiredAt = $this->expirationService->getExpiredDate('sms.verification.expired');
        $user->setTemporaryCodeExpiredAt($expiredAt)
            ->setNumberIsVerified(false);
        $this->entityManager->flush();
        $this->smsSender->send("+33".$user->getNumber(), $message);
    }

    public function findBy(array $criteria): ?User
    {
        return $this->userRepository->findOneBy($criteria);
    }

    public function codeIsValid(User $user, int $code): bool
    {
        $now = (new DateTimeImmutable())->getTimestamp();
        if ($now > $user->getTemporaryCodeExpiredAt()->getTimestamp()){
            return false;
        }
        if ($code != $user->getTemporaryNumberCode()){
            return false;
        }
        $user->setNumberIsVerified(true);
        $user->setTemporaryNumberCode(null)
            ->setTemporaryCodeExpiredAt(null)
            ->setNumberTokenVerification(null);
        $this->entityManager->flush();
        return true;
    }

    /**
     * @throws Exception
     */
    public function updateToken(User $user): string
    {
        $expiredAt = $this->expirationService->getExpiredDate('sms.verification.token.expired');
        $token = $this->tokenGenerator
                        ->setPayload([
                            'exp' => $expiredAt->getTimestamp(),
                            'sub' => $user->getId()
                        ])
                        ->generate();

        $user->setNumberTokenVerification($token);
        return $token;
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}