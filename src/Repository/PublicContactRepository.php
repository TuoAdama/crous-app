<?php

namespace App\Repository;

use App\Entity\PublicContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PublicContact>
 *
 * @method PublicContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicContact[]    findAll()
 * @method PublicContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicContact::class);
    }

//    /**
//     * @return PublicContact[] Returns an array of PublicContact objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PublicContact
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
