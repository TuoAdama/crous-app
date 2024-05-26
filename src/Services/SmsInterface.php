<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface SmsInterface
{
    public function send(string $to, string $message): void;
}