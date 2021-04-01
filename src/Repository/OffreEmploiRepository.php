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

    // /**
    //  * @return OffreEmploi[] Returns an array of OffreEmploi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OffreEmploi
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function countNbOffre(){
        return $this->createQueryBuilder('e')


            ->Select('COUNT(e.id)')


            // ->andWhere('e.NomProjet= :val')
            //->setParameter('val', $id)

            ->getQuery()

            ->getSingleScalarResult();
    }

    public function countOffreEmploiNonApprouve()
    {
        $query = $this->createQueryBuilder('r')
            ->select('count(r) as count')
            ->where('r.etat = 0');
        return $query->getQuery()->getResult();
    }
    public function OffreEmploiParMois()
    {
        $query = $this->createQueryBuilder('r')
            ->select(' MONTH(r.dateCreation) AS mois , count(r) as count')
            ->groupBy('mois');
        return $query->getQuery()->getResult();
    }

    public function countOffreEmploi()
    {
        $query = $this->createQueryBuilder('r')
            ->select('count(r) as count');
        return $query->getQuery()->getResult();
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


    public function updateDate(){
        return $this->getEntityManager()
            ->createQuery('DELETE FROM App\Entity\OffreEmploi o WHERE o.dateExpiration < CURRENT_DATE()')
            ->getResult();
    }
}
