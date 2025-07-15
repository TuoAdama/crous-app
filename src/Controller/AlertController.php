<?php

namespace App\Controller;

use App\DTO\Request\SearchRequestQuery;
use App\Entity\User;
use App\Services\AlertService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class AlertController extends AbstractController
{
    public function __construct(
        private readonly AlertService $alertService,
    )
    {
    }

    #[Route('/api/search/create-alert', methods: ['POST'])]
    public function createAlert(#[MapRequestPayload] SearchRequestQuery $query): JsonResponse
    {
        /** @var ?User $user */
        $user = $this->getUser();
        if ($user === null){
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }
        $criteria = $this->alertService->create($user, $query);
        return $this->json($criteria,Response::HTTP_CREATED);
    }
}
