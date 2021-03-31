<?php

namespace App\Repository;

use App\Entity\EventLoisir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventLoisir|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventLoisir|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventLoisir[]    findAll()
 * @method EventLoisir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventLoisirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventLoisir::class);
    }

    // /**
    //  * @return EventLoisir[] Returns an array of EventLoisir objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventLoisir
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function countEventLoisirNonApprouve()
    {
        $query = $this->createQueryBuilder('r')
            ->select('count(r) as count')
            ->where('r.Etat = 0');
        return $query->getQuery()->getResult();
    }

    public function OrderByDateD(){
        return $this->createQueryBuilder('e')
            ->where("e.Etat=1")
            ->orderBy('e.DateDebut','desc')
            ->getQuery()->getResult();
    }
    public function OrderByDateC(){
        return $this->createQueryBuilder('e')
            ->where("e.Etat=1")
            ->orderBy('e.DateDebut','ASC')
            ->getQuery()->getResult();
    }

    public function search($nom) {
        return $this->createQueryBuilder('e')
            ->Where('e.Labelle LIKE :labelle')
            ->setParameter('labelle', '%'.$nom.'%')
            ->getQuery()
            ->getResult();
    }

    public function getAllF($id){
        return $this->createQueryBuilder('ev')
            ->where('ev.Etat = 1')
            ->andWhere('ev.idFr != :id or ev.idFr IS NULL ')
            ->setParameter('id',$id)
            ->getQuery()->getResult();
    }
    public function getAllS($id){
        return $this->createQueryBuilder('fo')
            ->where('ev.Etat = 1')
            ->andWhere('ev.idSo != :id or ev.idSo IS NULL ')
            ->setParameter('id',$id)
            ->getQuery()->getResult();
    }
}
