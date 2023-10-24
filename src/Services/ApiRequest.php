<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiRequest
{

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface     $logger,
    )
    {
    }

    public function getSearchResult(string $url, array $requestBody): array
    {
        try {
            $response = $this->client->request('POST', $url, [
                'json' => $requestBody,
                'timeout' => 2.5
            ]);
            return $response->toArray();
        } catch
        (
        ServerExceptionInterface|
        TransportExceptionInterface|
        ClientExceptionInterface|
        RedirectionExceptionInterface|
        DecodingExceptionInterface $e
        ) {
            $this->logger->error($e->getMessage());
            return [];
        }
    }
}