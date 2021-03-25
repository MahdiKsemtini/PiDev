<?php

namespace App\Repository;

use App\Entity\Commentaires;
use App\Entity\Publications;
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
