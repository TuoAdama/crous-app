<?php

namespace App\Controller;

use App\DTO\EditEmailRequest;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\EmailVerificationService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/setting', name: 'setting_')]
#[IsGranted("IS_AUTHENTICATED_FULLY")]
class SettingController extends AbstractController
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordInterface,
        private readonly UserRepository              $userRepository,
        private readonly EmailVerificationService    $emailVerificationService,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/edit/email', name: 'edit_email', methods: ['POST'])]
    public function editEmail(#[MapRequestPayload] EditEmailRequest $editEmailRequest): JsonResponse
    {
        $errors = [];

        if(!$this->isCsrfTokenValid("edit-user", $editEmailRequest->token)){
            $errors["form"] = "Form is invalid";
        }
        /** @var User $user */
        $user = $this->getUser();
        if (!$this->passwordInterface->isPasswordValid($user, $editEmailRequest->password)){
            $errors["password"] = "password is invalid";
        }
        if ($this->userRepository->findBy(["email" => $editEmailRequest->email]) != null){
            $errors["email"] = "email is already used";
        }
        if (!empty($errors)){
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }
        $user->setEmail($editEmailRequest->email);
        $this->emailVerificationService->notify($user);

        return $this->json([
            'message' => $this->translator->trans("flash.messages.email.update"),
        ], Response::HTTP_OK);
    }
}
