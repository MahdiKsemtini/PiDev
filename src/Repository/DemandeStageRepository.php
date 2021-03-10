<?php

namespace App\Repository;

use App\Entity\DemandeStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemandeStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeStage[]    findAll()
 * @method DemandeStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeStage::class);
    }

    // /**
    //  * @return DemandeStage[] Returns an array of DemandeStage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DemandeStage
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
