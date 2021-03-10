<?php


namespace App\Service;




use Doctrine\ORM\EntityRepository;


class AdminService extends EntityRepository
{



    /**
     * @return \Doctrine\ORM\Query
     */
    public function AfficherOffreEmploiNonApprouve()
    {
        $em = $this->getEntityManager();
        $query =$em->createQuery("SELECT id_offreEmploi FROM App\Entity\offreEmploi e WHERE e.etat = 0 ;");
        $query->getResult();
        return $query;

    }
    /**
     * @return \Doctrine\ORM\Query
     */
    public function AfficherOffreStageNonApprouve()
    {
        $em = $this->getEntityManager();
        $query =$em->createQuery("SELECT id_offreStage FROM App\Entity\OffreStage e WHERE e.etat = 0 ;");
        return $query->getResult();

    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getDistinctAdminEmploi()
    {
        $em =$this->getEntityManager();
        $query = $em->createQuery("SELECT DISTINCT(id) FROM App\Entity\Admin  WHERE type='Admin des emplois' AND etat = 1 ;");
        return $query->getResult();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getDistinctAdminReclamation()
    {
        $em =$this->getEntityManager();
        $query = $em->createQuery("SELECT DISTINCT(id) FROM App\Entity\Admin  WHERE type='Admin des reclamations' AND etat = 1 ;");
        return $query->getResult();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getDistinctAdminPubEvent()
    {
        $em =$this->getEntityManager();
        $query = $em->createQuery("SELECT DISTINCT(id) FROM App\Entity\Admin  WHERE type='Admin des pubs & events' AND etat = 1 ;");
        return $query->getResult();
    }







}