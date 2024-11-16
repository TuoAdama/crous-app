<?php

namespace App\Repository;

use App\Entity\SearchCriteria;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchCriteria>
 *
 * @method SearchCriteria|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchCriteria|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchCriteria[]    findAll()
 * @method SearchCriteria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchCriteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchCriteria::class);
    }

    /**
     * @param SearchCriteria $searchCriteria
     * @return void
     */
    public function save(SearchCriteria $searchCriteria): void
    {
        $searchCriteria->setUpdatedAt(new DateTimeImmutable());
        $this->getEntityManager()->persist($searchCriteria);
        $this->getEntityManager()->flush();
    }

    /**
     * @return SearchCriteria[]
     */
    public function findAllAvailableCriteria(): array
    {
        return $this->createQueryBuilder('c')
            ->where("c.deletedAt is NULL")
            ->getQuery()
            ->getResult();
    }

    public function findFirstTop(int $page, int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->where("c.deletedAt is NULL")
            ->setFirstResult(($page-1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
