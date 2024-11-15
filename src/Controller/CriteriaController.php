<?php

namespace App\Controller;

use App\Entity\Request\CriteriaDeleteRequest;
use App\Entity\SearchCriteria;
use App\Entity\User;
use App\Form\NotificationType;
use App\Form\SearchCriteriaType;
use App\Repository\SearchCriteriaRepository;
use App\Services\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CriteriaController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface    $translator,
        private readonly SearchService          $searchService,
        private readonly SearchCriteriaRepository $searchCriteriaRepository,
    )
    {
    }

    #[Route('/criteria/{id?}', name: 'app_criteria')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(#[MapEntity] ?SearchCriteria $criteria, Request $request): Response
    {
        $searchCriteria = $criteria ?? new SearchCriteria();
        $form = $this->createForm(SearchCriteriaType::class, $searchCriteria);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $this->getUser();
            $this->addFlash('success', $this->translator->trans('flash.messages.update'));
            $this->searchService->save($searchCriteria,  $user, $request);
            return $this->redirectToRoute('app_index');
        }
        return $this->render('pages/criteria/criteria.html.twig',  [
            'identifier' => $this->getUser()->getUserIdentifier(),
            'form' => $form,
        ]);
    }

    #[Route('/criteria/delete', name: 'app_criteria_delete', methods: ['POST'])]
    public function delete(#[MapRequestPayload] CriteriaDeleteRequest $criteriaRequest): JsonResponse
    {
        $errorsResponses = [
            'type' => 'error',
            'message' => 'content not valid',
        ];
        if (!$this->isCsrfTokenValid('delete', $criteriaRequest->token)
        ) {
            return $this->json($errorsResponses, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->remove(
            $this->searchCriteriaRepository->find($criteriaRequest->id)
        );
        $this->entityManager->flush();

        return $this->json([
            'type' => 'success',
        ], Response::HTTP_OK);
    }
}
