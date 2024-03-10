<?php

namespace App\Services\Token;

use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class JWTServiceToken implements TokenGenerator
{

    private ?array $headers = null;
    private array $payload;

    private string $secret;
    private string $algorithm;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    )
    {
        $this->algorithm = $this->parameterBag->get('JWT_ALGORITHM');
        $this->secret = $this->parameterBag->get('JWT_SECRET');
    }

    public function setHeader(array $headers): TokenGenerator
    {
        $this->headers = $headers;
        return $this;
    }

    public function setPayload(array $payload): TokenGenerator
    {
        $this->payload = $payload;
        return $this;
    }

    public function generate(): string
    {
        if ($this->headers == null){
            return JWT::encode($this->payload, $this->secret, $this->algorithm);
        }
        return JWT::encode($this->payload, $this->secret, 'HS256', null, $this->headers);
    }
}