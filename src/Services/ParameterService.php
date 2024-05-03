<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParameterService
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    )
    {
    }

    public function get(string $key): string|int
    {
        return $this->parameterBag->get($key);
    }
}