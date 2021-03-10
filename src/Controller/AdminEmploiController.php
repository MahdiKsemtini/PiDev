<?php

namespace App\Controller;

use App\Entity\AdminEmploi;
use App\Repository\AdminEmploiRepository;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEmploiController extends AbstractController
{
    /**
     * @Route("/admin/emploi", name="admin_emploi")
     */
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
            'Liste' => 'AdminEmploiController',
        ]);
    }



    public function OffreStageToAdmin(AdminRepository $adminRepository, $idOffreStage)
    {
        $list = $adminRepository->findBy(array('type'=>'Admin des emplois', 'etat'=>1));
        $EntityManager = $this->getDoctrine()->getManager();
        foreach ($list as $l)
        {
            $AdminEmploi = new AdminEmploi();
            $AdminEmploi->setIdAE($l->getId());
            $AdminEmploi->setIdOffreStage($idOffreStage);
            $EntityManager->persist($AdminEmploi);
            $EntityManager->flush();
        }

    }
}
