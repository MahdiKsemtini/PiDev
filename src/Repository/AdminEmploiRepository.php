<?php

namespace App\Repository;

use App\Entity\AdminEmploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminEmploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminEmploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminEmploi[]    findAll()
 * @method AdminEmploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminEmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminEmploi::class);
    }

    // /**
    //  * @return AdminEmploi[] Returns an array of AdminEmploi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminEmploi
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return \Doctrine\ORM\Query
     */
    public function AfficherOffreEmploiNonApprouve()
    {
        $em = $this->getEntityManager();
        $query =$em->createQuery("SELECT id_offreEmploi FROM App\Entity\offreEmploi e WHERE e.etat = 0 ;");
        return $query->getResult();

    }


    /**
     * @return \Doctrine\ORM\Query
     */
    public function AfficherOffreStageNonApprouve()
    {
        $em = $this->getEntityManager();
        $query =$em->createQuery("SELECT id_offreStage FROM App\Entity\OffreStage e WHERE e.etat = 0 ;");
        return $query->getResult();

    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getDistinctAdminEmploi()
    {
        $em =$this->getEntityManager();
        $query = $em->createQuery("SELECT DISTINCT(id) FROM App\Entity\Admin  WHERE type='Admin des emplois' AND etat = 1 ;");
        return $query->getResult();
    }



    /**
     * @return \Doctrine\ORM\Query
     */
    public function getDistinctAdminPubEvent()
    {
        $em =$this->getEntityManager();
        $query = $em->createQuery("SELECT DISTINCT(id) FROM App\Entity\Admin  WHERE type='Admin des pubs & events' AND etat = 1 ;");
        return $query->getResult();
    }


}
