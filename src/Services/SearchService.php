<?php

namespace App\Services;

use App\Entity\SearchCriteria;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        private readonly ParameterBagInterface $params,
        private HttpClientInterface $client,
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
    }

    /**
     * @param SearchCriteria $searchCriteria
     * @return array
     */
    public function getRequestBody(SearchCriteria $searchCriteria): array
    {
        $location = $searchCriteria->getLocation();
        $extent = $location['properties']['extent'];
        if (count($extent) !== 4){
            throw new UnexpectedValueException("La taille de l'extent doit être égale à 4");
        }
        $lon1 = $extent[0]; $lat1 = $extent[1];
        $lon2 = $extent[2]; $lat2 = $extent[3];
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
     * @throws TransportExceptionInterface
     */
    public function search(array $requestBody): void
    {
        $response = $this->client->request('POST', $this->url, [
            'body' => $requestBody
        ]);
        dd(json_decode($response->getContent(), true));
    }

}