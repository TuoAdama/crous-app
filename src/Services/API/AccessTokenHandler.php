<?php

namespace App\Services\API;

use App\Entity\AccessToken;
use App\Repository\AccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{

    public function __construct(
        private readonly AccessTokenRepository $accessTokenRepository,
    )
    {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        /** @var ?AccessToken $userToken */
        $userToken = $this->accessTokenRepository->findOneBy(['token' => $accessToken]);
        if ($userToken == null || !$userToken->isValid()) {
            throw new BadCredentialsException('Invalid credentials.');
        }
        return new UserBadge($userToken->getUser()->getUserIdentifier());
    }
}