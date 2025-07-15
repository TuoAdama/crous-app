<?php

namespace App\Controller\API;

use App\DTO\Request\SearchRequestQuery;
use App\Entity\SearchCriteria;
use App\Entity\User;
use App\Repository\SearchCriteriaRepository;
use App\Services\SearchCriteriaValidator;
use App\Services\SearchService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api')]
class SearchController extends AbstractController
{

    public function __construct(
        private readonly SearchCriteriaValidator $searchValidator,
        private readonly SearchCriteriaRepository $criteriaRepository,
        private readonly SearchService $searchService,
    )
    {
    }

    #[Route('/search', name: 'search.store', methods: ['POST'])]
    public function index(
        Request $request
    ): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $content = $request->toArray();
        if ($user == null){
            return $this->json([
                'message' => 'You must be logged in',
            ], Response::HTTP_FORBIDDEN);
        }
        $searchCriteria = new SearchCriteria();
        $searchCriteria->setUser($user);
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


    #[Route('/search/results', name: 'search.results', methods: ['POST'])]
    public function getSearchResults(Request $request, SearchService $searchService): JsonResponse
    {
        $content = $request->getContent();

        if ($content === null){
            return $this->json([
                'message' => 'No content provided',
            ], Response::HTTP_BAD_REQUEST);
        }
        $data = json_decode($content, true);

        $searchCriteria = new SearchCriteria();

        $searchCriteria->setLocation($data['location'] ?? [])
                       ->setType($data['occupationModes'])
                       ->setPrice($data['price']['max'] ?? null);

        return $this->json($searchService->search($searchCriteria), Response::HTTP_OK);

    }


    #[Route('/search/location', name: 'search.location', methods: ['GET'])]
    public function getLocationByQuery(#[MapQueryString] SearchRequestQuery $query): JsonResponse
    {
        return $this->json($this->searchService->getLocationByQuery($query), Response::HTTP_OK);
    }
}
