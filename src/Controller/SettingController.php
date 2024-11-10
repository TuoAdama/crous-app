<?php

namespace App\Controller;

use App\Entity\Request\EditEmailRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/setting', name: 'setting_')]
#[IsGranted("IS_AUTHENTICATED_FULLY")]
class SettingController extends AbstractController
{
    #[Route('/edit/email', name: 'edit_email', methods: ['POST'])]
    public function editEmail(#[MapRequestPayload] EditEmailRequest $editEmailRequest): JsonResponse
    {
        return $this->json([$editEmailRequest]);
    }
}
