<?php

namespace App\Services;

use App\DTO\Request\SearchRequestQuery;
use App\Entity\SearchCriteria;
use App\Entity\User;
use App\Repository\SearchCriteriaRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AlertService
{
    public function __construct(
        private readonly SearchCriteriaRepository $criteriaRepository
    )
    {
    }

    public function create(User $user, SearchRequestQuery $query): SearchCriteria
    {
        $criteria = new SearchCriteria();
        $criteria->setType(isset($query->type) ? [$query->type] : [])
            ->setPrice($query->minPrice)
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
