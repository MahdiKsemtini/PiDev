<?php

namespace App\Repository;

use App\Entity\ReponseCondidat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseCondidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseCondidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseCondidat[]    findAll()
 * @method ReponseCondidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseCondidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseCondidat::class);
    }

    // /**
    //  * @return ReponseCondidat[] Returns an array of ReponseCondidat objects
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
    public function findOneBySomeField($value): ?ReponseCondidat
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
