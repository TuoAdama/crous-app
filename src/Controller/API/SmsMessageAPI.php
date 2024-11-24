<?php

namespace App\Controller\API;

use App\Services\SmsMessageService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class SmsMessageAPI extends AbstractController
{
    public function __construct(
        private readonly SmsMessageService $smsMessageService
    )
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/messages/unsent', methods: ['GET'])]
    public function getUnsentMessages(): JsonResponse
    {
        return $this->json($this->smsMessageService->getAllUnsentSmsMessages());
    }
}