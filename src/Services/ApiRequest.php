<?php

namespace App\Services;

use http\Exception\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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

    /**
     * @throws TransportExceptionInterface
     * @throws \InvalidArgumentException
     */
    public function postWithAuthentication(string $url, array $body, array $credentials): ResponseInterface
    {
        if (!isset($credentials['username']) && !isset($credentials['password'])){
            throw new InvalidArgumentException('username or password not exits');
        }
        return $this->client->request('POST', $url, [
            'auth_basic' => [$credentials['username'], $credentials['password']],
            'body' => $body
        ]);
    }


    public function get(string $url, array $parameters): array
    {
        $data = [];
        try {
            $response = $this->client->request('GET', $url, $parameters);
            if ($response->getStatusCode() !== 200) {
                return $data;
            }
            $data = json_decode($response->getContent(), true);

        } catch (TransportExceptionInterface
        | ClientExceptionInterface
        | ServerExceptionInterface
        | RedirectionExceptionInterface
        | ClientExceptionInterface $e) {
            $this->logger->error('Error during API request: ' . $e->getMessage());
        }
        return $data;
    }
}
