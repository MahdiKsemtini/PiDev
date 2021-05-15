<?php

namespace App\Repository;

use App\Entity\Publications;
use App\Entity\Freelancer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Publications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publications[]    findAll()
 * @method Publications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publications::class);
    }

    // /**
    //  * @return Publications[] Returns an array of Publications objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Publications
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function trierdatep()
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.date_publication', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function getNbPub()
    {

        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id) AS pub,DATE(p.date_publication) AS date')
            ->groupBy('date');


        return $qb->getQuery()
            ->getResult();
    }

    public function getPubs()
    {

        $qb = $this->createQueryBuilder('p')
            ->select('p.id,p.description,p.image,p.date_publication,f.nom,f.prenom')
            ->innerJoin('p.freelancer', 'f', Join::WITH, 'p.freelancer = f');


        return $qb->getQuery()
            ->getResult();
    }

    public function findByKey($keyword){
        $query = $this->createQueryBuilder('p')
            ->where('p.description LIKE :key')
            ->setParameter('key' , '%'.$keyword.'%')->getQuery();

        return $query->getResult();
    }
}
