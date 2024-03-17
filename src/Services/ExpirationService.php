<?php

namespace App\Services;

use DateInterval;
use DateTimeImmutable;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ExpirationService
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function getExpiredDate(string $parameterKey): DateTimeImmutable
    {
        $expiredIn = $this->parameterBag->get($parameterKey);
        return (new DateTimeImmutable())->add(new DateInterval('PT'.$expiredIn.'S'));
    }
}