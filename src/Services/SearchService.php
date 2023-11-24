<?php

namespace App\Services;

use App\Entity\SearchCriteria;
use App\Entity\SearchResult;
use App\Entity\User;
use App\Message\SearchResultMessage;
use App\Repository\SearchCriteriaRepository;
use App\Repository\SearchResultRepository;
use Doctrine\Common\Collections\Criteria;
use Psr\Log\LoggerInterface;
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
        $results = [];
        $ids = [];
        $criterias = $this->criteriaRepository->findAllAvailableCriteria();
        if (count($criterias) != 0) {
            foreach ($criterias as $criteria) {
                $searchResult = $this->search($criteria);
                if (count($searchResult) == 0) {
                    continue;
                }
                if (!key_exists('results', $searchResult)) {
                    continue;
                }
                $searchResult = $searchResult['results'];
                if (!key_exists('items', $searchResult) || count($searchResult['items']) == 0) {
                    continue;
                }
                $items = $searchResult['items'];
                // Check if information are already registered
                $exist = $this->comparisonService->exists($criteria, $items);
                if ($exist){
                    $this->logger->info('criteria_id = '.$criteria->getId());
                    continue;
                }
                $ids[] = $criteria->getId();
                $results[] = [
                    'criteria' => $criteria,
                    'results' => $searchResult,
                ];
            }
        }
        if (count($results)) {
            $this->storeSearchResults($results);
        }
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
     * @return void
     */
    function getResults(User $user): array|null
    {
        /** @var SearchCriteria|bool $criteria */
        $criteria = $user->getSearchCriterias()->first();
        if ($criteria === false){
            return null;
        }
        /** @var SearchResult|bool $results */
        $results = $criteria->getSearchResults()->first();
        if ($results === false){
            return null;
        }
        return [
            'link' => $this->getLink($criteria),
            'results' => $results->getResults()
        ];
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

}