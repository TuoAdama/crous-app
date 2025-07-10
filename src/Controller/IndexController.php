<?php

namespace App\Controller;

use App\DTO\Request\SearchRequestQuery;
use App\Entity\User;
use App\Services\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly SearchService $searchService
    )
    {
    }


    #[Route('/', name: 'app_home')]
    public function home(#[MapQueryString] ?SearchRequestQuery $query): Response
    {
        if ($query === null) {
            $query = new SearchRequestQuery();
        }

        $results = $this->searchService->getLocationByQuery($query);

        return $this->render('pages/home/index.html.twig', [
            'config' => $this->searchService->getConfigs(),
            'data' => $results,
            'params' => [
                'extent' => $query->extent,
                'type' => $query->type,
                'minArea' => $query->minArea,
                'minPrice' => $query->minPrice
            ]
        ]);
    }

    #[Route('/profile', name: 'app_index')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $criteriaWithResults = $this->searchService->getCriteriaWithResults($user);
        return $this->render('pages/dashboard.html.twig', [
            'criteriaWithResults' => $criteriaWithResults
        ]);
    }
}
