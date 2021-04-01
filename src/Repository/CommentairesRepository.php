<?php

namespace App\Repository;

use App\Entity\Commentaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaires[]    findAll()
 * @method Commentaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaires::class);
    }

    // /**
    //  * @return Commentaires[] Returns an array of Commentaires objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commentaires
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getNB()
    {

        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id) AS com, (c.id_pub) AS pub')
            ->groupBy('pub');


        return $qb->getQuery()
            ->getResult();
    }

    public function trierdatec()
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.date_com', 'DESC');

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
}
