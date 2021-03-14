<?php

namespace App\Repository;

use App\Entity\OffreEmploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OffreEmploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreEmploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreEmploi[]    findAll()
 * @method OffreEmploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreEmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreEmploi::class);
    }

    public function countNbOffre(){
        return $this->createQueryBuilder('e')
       
      
        ->Select('COUNT(e.id)') 
        
       
       // ->andWhere('e.NomProjet= :val')
        //->setParameter('val', $id)
      
        ->getQuery()
    
        ->getSingleScalarResult();
       }

       public function findOffresByOne($q){

        $qd=$this->createQueryBuilder('u');
        $qd
        ->addSelect('u.nomProjet')
        ->addSelect('u.competences')
        
        ->addSelect('u.description')
        ->addSelect('u.domaine')
        ->addSelect('u.fichier')
        ->addSelect('c.Nom as nomClasse')
         ->innerJoin('u.societe','c')
         ->where('c.id = : :val')
         ->setParameter('val', $q)
         ->getQuery()
         ->getResult();

        

    }

    public function findEmploiParNom($nom){
        return $this->createQueryBuilder('emploi')
            ->where('emploi.nomProjet LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
            ->getQuery()
            ->getResult();
    }

    public function findDomaine($domaine){
        return $this->createQueryBuilder('emploi')
        ->where('emploi.domaine = :domaine')
        ->setParameter('domaine',$domaine)
        ->getQuery()
            ->getResult();


    }

}
