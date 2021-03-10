<?php

namespace App\Repository;

use App\Entity\AdminReclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminReclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminReclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminReclamation[]    findAll()
 * @method AdminReclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminReclamation::class);
    }

    // /**
    //  * @return AdminReclamation[] Returns an array of AdminReclamation objects
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
    public function findOneBySomeField($value): ?AdminReclamation
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
    public function getDistinctAdminReclamation()
    {
        $em =$this->getEntityManager();
        $query = $em->createQuery("SELECT a.id FROM admin a WHERE a.type='Admin des reclamations' AND a.etat = 1");
        return $query->getResult();
    }
}
