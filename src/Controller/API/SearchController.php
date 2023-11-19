<?php

namespace App\Controller\API;

use App\Entity\SearchCriteria;
use App\Entity\User;
use App\Repository\SearchCriteriaRepository;
use App\Services\SearchCriteriaValidator;
use App\Services\UserService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api')]
class SearchController extends AbstractController
{

    public function __construct(
        private readonly SearchCriteriaValidator $searchValidator,
        private readonly SearchCriteriaRepository $criteriaRepository,
        private readonly UserService $userService,
    )
    {
    }

    #[Route('/search', name: 'search.store', methods: ['POST'])]
    public function index(
        Request $request
    ): JsonResponse
    {
        $user = $this->getUser();
        $content = $request->toArray();
        if ($user == null){
            return $this->json([
                'message' => 'You must be logged in',
            ], Response::HTTP_FORBIDDEN);
        }
        $searchCriteria = new SearchCriteria();
        $searchCriteria->setLocation($content['location'] ?? []);
        $searchCriteria->setType($content['type'] ?? []);
        if (is_numeric($content['price'])){
            $searchCriteria->setPrice($content['price']);
        }
        $errors = $this->searchValidator->validate($searchCriteria);
        if (count($errors)){
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $searchCriteria->setCreatedAt(new DateTimeImmutable());
        $this->criteriaRepository->save($searchCriteria);
        return $this->json($request->getContent());
    }
}
