<?php

namespace App\Repository;

use App\Entity\ListReponsesCondidat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListReponsesCondidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListReponsesCondidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListReponsesCondidat[]    findAll()
 * @method ListReponsesCondidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListReponsesCondidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListReponsesCondidat::class);
    }

    // /**
    //  * @return ListReponsesCondidat[] Returns an array of ListReponsesCondidat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListReponsesCondidat
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
