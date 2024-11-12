<?php

namespace App\Controller;

use App\DTO\EditEmailRequest;
use App\DTO\EditNumberRequest;
use App\DTO\EditUserBaseInformation;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\EmailVerificationService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
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
        private readonly TranslatorInterface         $translator,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserService $userService,
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
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->setEmail($editEmailRequest->email);
        $this->emailVerificationService->notify($user);

        return $this->json([
            'message' => $this->translator->trans("flash.messages.email.update"),
            'user' => $user,
        ], Response::HTTP_OK);
    }


    #[Route('/edit/base-information', name: 'edit_base_information', methods: ['POST'])]
    public function editBaseInformation(#[MapRequestPayload] EditUserBaseInformation $baseInformation): JsonResponse
    {
        if (!$this->isCsrfTokenValid('edit-user', $baseInformation->token)){
            return $this->json([
                'form' => $this->translator->trans('form.errors.invalid'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        /** @var User $user */
        $user = $this->getUser();
        $user->setUsername($baseInformation->username)
            ->setNotifyByEmail($baseInformation->notifyByEmail)
            ->setNotifyByNumber($baseInformation->notifyByNumber);
        $this->entityManager->flush();
        return $this->json([
            'message' => $this->translator->trans("flash.messages.baseinformation.update"),
            'user' => $user,
        ], Response::HTTP_OK);
    }


    /**
     * @throws Exception
     */
    #[Route('/resend/email/verification', name: 'resend_mail_verification', methods: ['GET'])]
    public function resendEmailVerification(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->emailVerificationService->notify($user);
        return $this->json([
            'message' => $this->translator->trans("flash.messages.email.resend"),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/resend/number/verification', name: 'resend_number_verification', methods: ['POST'])]
    public function onUpdateNumber(#[MapRequestPayload] EditNumberRequest $request): Response
    {
        if (!$this->isCsrfTokenValid('edit-user', $request->token)){
            return $this->json([
                'message' => $this->translator->trans('form.errors.invalid'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($this->userRepository->findBy(['number' => $request->number]) != null){
            return $this->json([
                'message' => $this->translator->trans('form.errors.number.exist'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        /** @var User $user */
        $user = $this->getUser();
        $user->setNumber($request->number);
        $this->userService->verifyNumber($user);
        $token = $this->userService->updateToken($user);
        $this->userService->flush();
        return $this->json([
            'message' => $this->translator->trans('sms.messages.number.verification'),
        ], Response::HTTP_OK);
    }
}
