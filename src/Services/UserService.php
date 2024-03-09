<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly CodeGenerator $codeGenerator,
        private readonly SmsInterface $smsSender,
        private readonly EntityManagerInterface $entityManager,
        private readonly ParameterBagInterface $parameterBag,
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
        $expiredIn = $this->parameterBag->get('sms.verification.expired');
        $expiredAt = (new DateTimeImmutable())->add(new DateInterval('PT'.$expiredIn.'S'));
        $user->setTemporaryCodeExpiredAt($expiredAt);
        $this->entityManager->flush();
        $this->smsSender->send("+33".$user->getNumber(), $message);
    }

    public function findBy(array $criteria): ?User
    {
        return $this->userRepository->findOneBy($criteria);
    }


    /**
     * @throws Exception
     */
    public function codeIsValid(User $user, int $code): bool
    {
        if ($user->getTemporaryCodeExpiredAt() == null || $user->getTemporaryNumberCode() == null){
            throw new Exception("Verification information doesn't exists");
        }
        $now = (new DateTimeImmutable())->getTimestamp();
        if ($now > $user->getTemporaryCodeExpiredAt()->getTimestamp()){
            return false;
        }
        if ($code != $user->getTemporaryNumberCode()){
            return false;
        }
        $user->setNumberIsVerified(true);
        $user->setTemporaryNumberCode(null)
            ->setTemporaryCodeExpiredAt(null);
        $this->entityManager->flush();
        return true;
    }
}