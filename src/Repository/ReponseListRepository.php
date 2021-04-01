<?php

namespace App\Repository;

use App\Entity\ReponseList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseList[]    findAll()
 * @method ReponseList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseList::class);
    }

    // /**
    //  * @return ReponseList[] Returns an array of ReponseList objects
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
    public function findOneBySomeField($value): ?ReponseList
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
