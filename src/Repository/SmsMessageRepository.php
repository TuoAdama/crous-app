<?php

namespace App\Repository;

use App\Entity\SmsMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SmsMessage>
 *
 * @method SmsMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmsMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmsMessage[]    findAll()
 * @method SmsMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmsMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmsMessage::class);
    }


    public function getAllUnsentSmsMessages(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.sent = :sent')
            ->setParameter('sent', false)
            ->getQuery()
            ->getResult();
    }
}
