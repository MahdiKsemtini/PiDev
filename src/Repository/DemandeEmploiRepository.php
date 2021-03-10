<?php

namespace App\Repository;

use App\Entity\DemandeEmploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemandeEmploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeEmploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeEmploi[]    findAll()
 * @method DemandeEmploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeEmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeEmploi::class);
    }

    // /**
    //  * @return DemandeEmploi[] Returns an array of DemandeEmploi objects
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
    public function findOneBySomeField($value): ?DemandeEmploi
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
