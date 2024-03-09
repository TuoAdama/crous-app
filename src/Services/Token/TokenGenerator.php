<?php

namespace App\Services\Token;

use App\Entity\User;

interface TokenGenerator
{
    public function setHeader(array $headers): TokenGenerator;

    public function setPayload(array $payload): TokenGenerator;

    public function generate(): string;
}