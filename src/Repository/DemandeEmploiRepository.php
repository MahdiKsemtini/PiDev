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
    public function OrderBydateCreationQb()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.dateCreation','ASC')
            ->getQuery()->getResult();

    }


    public  function SearchNomSociete($data){
        return $this->createQueryBuilder('d')
            ->where('d.nomsociete LIKE :data')
            ->setParameter('data','%'.$data.'%')
            ->getQuery()->getResult();

    }







    public function searchDomaine($domaine){
        return $this->createQueryBuilder('demande')
            ->where('demande.domaine = :domaine')
            ->setParameter('domaine',$domaine)
            ->getQuery()
            ->getResult();


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


    public function findOneBySomeField($value): ?DemandeEmploi
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function DemandeEmploiParMois()
    {
        $query = $this->createQueryBuilder('r')
            ->select(' MONTH(r.dateCreation) AS mois , count(r) as count')
            ->groupBy('mois');
        return $query->getQuery()->getResult();
    }

    public function countDemandeEmploi()
    {
        $query = $this->createQueryBuilder('r')
            ->select('count(r) as count');
        return $query->getQuery()->getResult();
    }
}
