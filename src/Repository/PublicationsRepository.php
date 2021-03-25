<?php

namespace App\Repository;

use App\Entity\Publications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findByKey($keyword){
        $query = $this->createQueryBuilder('p')
            ->where('p.description LIKE :key')->orWhere('p.id_utilisateur LIKE :key')
            ->setParameter('key' , '%'.$keyword.'%')->getQuery();

        return $query->getResult();
    }

}
