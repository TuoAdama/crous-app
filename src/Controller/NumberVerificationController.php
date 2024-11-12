<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\VerificationNumberType;
use App\Services\Token\SmsTokenValidator;
use App\Services\UserService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class NumberVerificationController extends AbstractController
{
    public function __construct(
        private readonly SmsTokenValidator $smsTokenValidator,
        private readonly UserService $userService,
    )
    {
    }

    #[Route('/setting/verification/number/{token}', name: 'user.verification.number')]
    public function verification(Request $request, string $token): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->smsTokenValidator->validate($user, $token);

        $form = $this->createForm(VerificationNumberType::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $this->getUser();
            $isValid = $this->userService->codeIsValid($user, $form->getData()['code']);
            if (!$isValid){
                $this->addFlash('danger', 'Code incorrect');
                return $this->redirectToRoute('user.verification.number', [
                    'token' => $token
                ]);
            }
            $this->addFlash('success', 'Numéro mis à jour');
            return $this->redirectToRoute('user.setting');
        }
        $expiredAt = $user->getTemporaryCodeExpiredAt();
        $now = new DateTimeImmutable();
        $seconds = 0;
        if ($expiredAt > $now){
            $seconds = $expiredAt->getTimestamp() - $now->getTimestamp();
        }
        return $this->render('pages/number-verification.html.twig',[
            'form' => $form,
            'seconds' => $seconds,
        ]);
    }

}