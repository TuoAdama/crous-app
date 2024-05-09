<?php

namespace App\Services;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TwilioService implements SmsInterface
{
    private string $token;
    private string $url;
    private string $accountSID;

    private string $senderNumber;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly ApiRequest            $apiRequest,
        private LoggerInterface                $logger,
    )
    {
        $this->token = $this->parameterBag->get('twilio_auth_token');
        $this->url = $this->parameterBag->get('twilio_url');
        $this->accountSID = $this->parameterBag->get('twilio_account_sid');
        $this->senderNumber = $this->parameterBag->get('twilio_sender_number');
    }

    public function send(string $to, string $message): ?ResponseInterface
    {
        $body = [
            'To' => "+33".$to,
            'From' => $this->senderNumber,
            'Body' => $message,
        ];
        try {
            return $this->apiRequest->postWithAuthentication($this->url, $body, [
                'username' => $this->accountSID,
                'password' => $this->token
            ]);

        } catch (TransportExceptionInterface|InvalidArgumentException $e) {
            $this->logger->error('SMS failed when sending to {number}', [
                'number' => $to,
            ]);
        }
        return null;
    }
}