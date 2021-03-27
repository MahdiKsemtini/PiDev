<?php

namespace App\Repository;

use App\Entity\OffreStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OffreStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreStage[]    findAll()
 * @method OffreStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreStage::class);
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

    public function findStageParNom($nom){
        return $this->createQueryBuilder('stage')
            ->where('stage.nomProjet LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
            ->getQuery()
            ->getResult();
    }

    public function findDomaine($domaine){
        return $this->createQueryBuilder('stage')
        ->where('stage.domaine = :domaine')
        ->setParameter('domaine',$domaine)
        ->getQuery()
            ->getResult();


    }


    public function updateDate(){
        return $this->getEntityManager()
            ->createQuery('DELETE FROM App\Entity\OffreStage s WHERE s.dateExpiration < CURRENT_DATE()')
            ->getResult();
    }
}