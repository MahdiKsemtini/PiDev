<?php

namespace App\Controller;

use App\Entity\AdminEmploi;
use App\Notifications\CreationCompteNotification;
use App\Repository\AdminEmploiRepository;
use App\Repository\AdminRepository;
use App\Repository\OffreEmploiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEmploiController extends AbstractController
{

    /**
     * @var CreationCompteNotification
     */
    private $notify_creation;


    public function __construct(CreationCompteNotification $notify_creation)
    {
        $this->notify_creation = $notify_creation;
    }
    /**
     * @Route("/admin/emploi", name="admin_emploi")
     */
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
            'Liste' => 'AdminEmploiController',
        ]);
    }



    public function OffreStageToAdmin(AdminRepository $adminRepository, $idOffreStage,$SocieteEmail)
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

            $this->notify_creation->notify($SocieteEmail,$l->getLogin());
        }

    }

    public function OffreEmploiToAdmin(AdminRepository $adminRepository, $idOffreEmploi,$SocieteEmail,OffreEmploiRepository $offreEmploiRepository)
    {
        $list = $adminRepository->findBy(array('type'=>'Admin des emplois', 'etat'=>1));
        $EntityManager = $this->getDoctrine()->getManager();
        foreach ($list as $l)
        {
            $AdminEmploi = new AdminEmploi();
            $AdminEmploi->setIdAE($l->getId());
            $AdminEmploi->setIdOffreEmploi($idOffreEmploi);
            $EntityManager->persist($AdminEmploi);
            $EntityManager->flush();

            $this->notify_creation->notifyEmploi($SocieteEmail,$l->getLogin(),$idOffreEmploi,$offreEmploiRepository);
        }

    }


}
