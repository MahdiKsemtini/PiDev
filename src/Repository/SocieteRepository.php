<?php

namespace App\Repository;

use App\Entity\Societe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Societe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Societe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Societe[]    findAll()
 * @method Societe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Societe::class);
    }

    

    public function countNbOffre($id){
        return $this->createQueryBuilder('e')
       
      
        ->addSelect('COUNT(e.offreEmplois)')
        
       
        ->andWhere('e.offreEmplois= :val')
        ->setParameter('val', $id)
      
        ->getQuery()
    
        ->getResult();
       }

       public function findSociete($soc){
        return $this->createQueryBuilder('s')
        ->where('s.nom = :soc')
        ->setParameter('soc',$soc)
        ->getQuery()
            ->getResult();


    }


     
}
