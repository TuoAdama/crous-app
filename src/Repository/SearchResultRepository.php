<?php

namespace App\Repository;

use App\Entity\SearchCriteria;
use App\Entity\SearchResult;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchResult>
 *
 * @method SearchResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchResult[]    findAll()
 * @method SearchResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchResult::class);
    }


    /**
     * @param array $results
     * @return void
     */
    public function updateOrStoreAll(array $results): void
    {
        //TODO Remove old result and save the new
        $currentDate = new DateTimeImmutable();
        $em = $this->getEntityManager();
        foreach ($results as $result) {
            /** @var SearchCriteria $criteria */
            $criteria = $result['criteria'];
            $criteriaResult = $result['results']['items'];
            $searchResult = new SearchResult();
            $searchResult->setResults($criteriaResult)
                ->setSearchCriteria($criteria)
                ->setCreatedAt($currentDate)
                ->setUpdatedAt($currentDate);
            $em->persist($searchResult);
        }
        $em->flush();
    }

    function getResultAvailable(SearchCriteria $searchCriteria): ?SearchResult
    {
        try {
            return $this->createQueryBuilder('s')
                ->where('s.searchCriteria = :criteria')
                ->andWhere('s.deletedAt is NULL')
                ->setParameter('criteria', $searchCriteria)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
