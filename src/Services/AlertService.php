<?php

namespace App\Services;

use App\DTO\Request\SearchRequestQuery;
use App\Entity\SearchCriteria;
use App\Entity\User;
use App\Enum\TypeLocation;
use App\Repository\SearchCriteriaRepository;
use DateTimeImmutable;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AlertService
{
    public function __construct(
        private readonly SearchCriteriaRepository $criteriaRepository,
        #[Autowire(param: 'min')]
        private readonly int $minPrice,
    )
    {
    }

    public function create(User $user, SearchRequestQuery $query): SearchCriteria
    {
        $criteria = new SearchCriteria();
        $type = !empty($query->type) ? [$query->type] : [TypeLocation::COLOCATION->value, TypeLocation::INDIVIDUAL->value];
        $criteria->setType($type)
            ->setPrice($query->minPrice ?? $this->minPrice)
            ->setUser($user)
            ->setLocation(
                [
                    'properties' => [
                        'extent' => explode(',', $query->extent),
                        'name' => $query->name,
                    ]
                ]
            )
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable());
        $this->criteriaRepository->save($criteria);

        return $criteria;
    }
}
