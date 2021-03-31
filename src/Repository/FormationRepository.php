<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    // /**
    //  * @return Formation[] Returns an array of Formation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function OrderByDateD(){
        return $this->createQueryBuilder('f')
            ->where("f.Etat=1")
            ->orderBy('f.DateDebut','desc')
            ->getQuery()->getResult();
    }
    public function OrderByDateC(){
        return $this->createQueryBuilder('f')
            ->where("f.Etat=1")
            ->orderBy('f.DateDebut','ASC')
            ->getQuery()->getResult();
    }
    public function search($nom) {
        return $this->createQueryBuilder('f')
            ->Where('f.Labelle LIKE :labelle')
            ->setParameter('labelle', '%'.$nom.'%')
            ->getQuery()
            ->getResult();
    }
    public function getAllF($id){
        return $this->createQueryBuilder('fo')
            ->where('fo.Etat = 1')
            ->andWhere('fo.idFr != :id or fo.idFr IS NULL ')
            ->setParameter('id',$id)
            ->getQuery()->getResult();
    }
}
