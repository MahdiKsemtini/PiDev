<?php

namespace App\Repository;

use App\Entity\ReviewsTextual;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReviewsTextual|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewsTextual|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewsTextual[]    findAll()
 * @method ReviewsTextual[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewsTextualRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewsTextual::class);
    }

    // /**
    //  * @return ReviewsTextual[] Returns an array of ReviewsTextual objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReviewsTextual
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
