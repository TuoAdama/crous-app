<?php

namespace App\Controller\API;

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
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api')]
class SearchController extends AbstractController
{

    public function __construct(
        private readonly SearchCriteriaValidator $searchValidator,
        private readonly SearchCriteriaRepository $criteriaRepository,
        private readonly SearchService $searchService
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
    public function getSearchResults(Request $request, SearchService $searchService)
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
    public function getLocationByQuery(Request $request): JsonResponse
    {
        $parameters = [];
        if ($request->query->get('q')){
            $parameters['q'] = $request->query->get('q');
        }
        if ($request->query->get('type')){
            $parameters['type'] = [$request->query->get('type')];
        }
        if ($request->query->get('price_min')){
            $parameters['price_min'] = $request->query->get('price_min');
        }
        if ($request->query->get('area')){
            $parameters['area'] = $request->query->get('area');
        }

        return $this->json($this->searchService->getLocationByQuery($parameters), Response::HTTP_OK);
    }
}
