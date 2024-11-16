<?php

namespace App\Services;

use App\DTO\Response\CriteriaResultResponse;
use App\Entity\SearchCriteria;
use App\Entity\User;
use App\Message\SearchResultMessage;
use App\Repository\SearchCriteriaRepository;
use App\Repository\SearchResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use UnexpectedValueException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SearchService
{

    private string $url;
    private int $precision;
    private int $idTool;
    private bool $needAggregation;
    private ?string $residence;
    private ?string $sector;
    private int $page;
    private array $occupationModes;
    private int $min;
    private array $equipment;
    private int $multiple;

    private string $resultLink;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        private readonly ParameterBagInterface    $params,
        private readonly LoggerInterface          $logger,
        private readonly SearchCriteriaRepository $criteriaRepository,
        private readonly SearchResultRepository   $searchResultRepository,
        private readonly ApiRequest               $apiRequest,
        private readonly ComparisonService $comparisonService,
        private readonly MessageBusInterface $bus,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        $this->precision = $this->params->get('precision');
        $this->idTool = $this->params->get('idTool');
        $this->needAggregation = $this->params->get('need_aggregation');
        $this->residence = $this->params->get('residence');
        $this->sector = $this->params->get('sector');
        $this->page = $this->params->get('page');
        $this->occupationModes = $this->params->get('occupationModes');
        $this->min = $this->params->get('min');
        $this->equipment = $this->params->get('equipment');
        $this->multiple = $this->params->get('multiple');
        $this->url = $this->params->get('url');
        $this->resultLink = $this->params->get('result.link');
    }

    /**
     * @param SearchCriteria $searchCriteria
     * @return array
     */
    public function getRequestBody(SearchCriteria $searchCriteria): array
    {
        $location = $searchCriteria->getLocation();
        $extent = $location['properties']['extent'];
        if (count($extent) !== 4) {
            throw new UnexpectedValueException("La taille de l'extent doit être égale à 4");
        }
        $lon1 = $extent[0];
        $lat1 = $extent[1];
        $lon2 = $extent[2];
        $lat2 = $extent[3];
        return [
            'idTool' => $this->idTool,
            'need_aggregation' => $this->needAggregation,
            'page' => $this->page,
            'residence' => $this->residence,
            'sector' => $this->sector,
            'precision' => $this->precision,
            'occupationModes' => $this->occupationModes,
            'equipment' => $this->equipment,
            'price' => [
                'min' => $this->min,
                'max' => $searchCriteria->getPrice() * $this->multiple,
            ],
            'location' => [
                [
                    'lon' => $lon1,
                    'lat' => $lat1,
                ],
                [
                    'lon' => $lon2,
                    'lat' => $lat2,
                ],
            ]
        ];
    }

    /**
     * @param SearchCriteria $criteria
     * @return array
     */
    public function search(SearchCriteria $criteria): array
    {
        $this->logger->info("Récupération des logements disponibles");
        $requestBody = $this->getRequestBody($criteria);
        return $this->apiRequest->getSearchResult($this->url, $requestBody);
    }


    public function run(): void
    {
        $allCriteria = $this->criteriaRepository->findAllAvailableCriteria();
        if (count($allCriteria) != 0) {
            $results = [];
            foreach ($allCriteria as $criteria) {
                $searchResult = $this->search($criteria);
                if (!$this->isResponseBodyContainsResults($searchResult, $criteria)) {
                    $criteriaOldResult =  $criteria->getSearchResults();
                    if ($criteriaOldResult->count() > 0){
                        $criteriaOldResult->clear();
                    }
                    continue;
                }
                $results[] = [
                    'criteria' => $criteria,
                    'results' => $searchResult['results'],
                ];
            }
            $this->entityManager->flush();
            if (count($results)) {
                $this->storeSearchResults($results);
            }
        }
    }


    /**
     * Check if response body from api request contains new criteria response
     * @param array $searchResult
     * @param $criteria
     * @return bool
     */
    public function isResponseBodyContainsResults(array $searchResult, $criteria): bool
    {
        if (count($searchResult) === 0 || !key_exists('results', $searchResult)) {
            return false;
        }
        $results = $searchResult['results'];
        if (!key_exists('items', $results) || count($results['items']) == 0) {
            return false;
        }

        $exist = $this->comparisonService->exists($criteria, $results['items']);
        if ($exist){
            $this->logger->info('criteria_id = '.$criteria->getId());
            return false;
        }
        return true;
    }

    /**
     * @param array $results
     * @return void
     */
    public function storeSearchResults(array $results): void
    {
       $results = $this->searchResultRepository->updateOrStoreAll($results);
       $ids = [];
        foreach ($results as $result) {
            $ids[] = $result->getId();
       }
        $this->bus->dispatch(new SearchResultMessage($ids));
    }


    /**
     * @param User $user
     * @return array
     */
    function getCriteriaWithResults(User $user): array
    {
        $allCriteria = $this->getUserCriteria($user);
        $results = [];
        foreach ($allCriteria as $criteria) {
            $criteriaResult = $criteria->getSearchResults();
            $criteriaResultResponse = new CriteriaResultResponse($criteria);

            if (count($criteriaResult) !== 0) {
                $criteriaResultResponse->setLink($this->getLink($criteria));
            }
            $results[] = $criteriaResultResponse;
        }
        return $results;
    }


    /**
     * @param SearchCriteria $criteria
     * @return string
     */
    function getLink(SearchCriteria $criteria): string
    {
       $lat1 = $criteria->getLat1();
       $lon1 = $criteria->getLon1();
       $lat2 = $criteria->getLat2();
       $lon2 = $criteria->getLon2();
       return str_replace(['LAT-1', 'LON-1', 'LAT-2', 'LON-2'], [$lat1, $lon1, $lat2, $lon2], $this->resultLink);
    }


    public function save(SearchCriteria $searchCriteria, User $user, Request $request): void
    {
        $location = $request->request->all('search_criteria')['location'];
        $location = json_decode($location, true);
        $searchCriteria->setLocation($location);
        $searchCriteria->setUser($user);
        $this->entityManager->persist($searchCriteria);
        $this->entityManager->flush();
    }

    public function getUserCriteria(User $user): array
    {
        return $this->criteriaRepository->findBy(['user' => $user]);
    }

}