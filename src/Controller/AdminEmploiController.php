<?php

namespace App\Controller;

use App\Entity\AdminEmploi;
use App\Notifications\CreationCompteNotification;
use App\Repository\AdminEmploiRepository;
use App\Repository\AdminRepository;
use App\Repository\OffreEmploiRepository;
use App\Repository\OffreStageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param OffreEmploiRepository $offreEmploiRepository
     * @param OffreStageRepository $offreStageRepository
     * @return Response
     */
    public function index(PaginatorInterface $paginator,Request $request,OffreEmploiRepository $offreEmploiRepository,OffreStageRepository $offreStageRepository): Response
    {

        $ListeOffreEmploi = $offreEmploiRepository->findBy(array('etat'=>0));
        $ListeOffreStage = $offreStageRepository->findBy(array('etat'=>0));
        $ListeOffreEmploi = $paginator->paginate(
        // Doctrine Query, not results
            $ListeOffreEmploi,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        $ListeOffreStage = $paginator->paginate(
        // Doctrine Query, not results
            $ListeOffreStage,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('admin_emploi/index.html.twig', [
            'Liste' => 'AdminEmploiController',
            'ListeOffreEmploi'=>$ListeOffreEmploi,
            'ListeOffreStage'=>$ListeOffreStage,

        ]);
    }

    public function OffreEmploiToAdmin(AdminRepository $adminRepository, $idOffreEmploi,$SocieteEmail,OffreEmploiRepository $offreEmploiRepository,AdminEmploiRepository $adminEmploiRepository)
    {
        $list = $adminRepository->findBy(array('type' => 'Admin des emplois', 'etat' => 1));
        $EntityManager = $this->getDoctrine()->getManager();

        foreach ($list as $l) {
            $ListeadminExiste = $adminEmploiRepository->findBy(array('id_A_E' => $l->getId(), 'id_Offre_Emploi' => null));
            if ($ListeadminExiste == null) {
                $AdminEmploi = new AdminEmploi();
                $AdminEmploi->setIdAE($l->getId());
                $AdminEmploi->setIdOffreEmploi($idOffreEmploi);
                $EntityManager->persist($AdminEmploi);
                $EntityManager->flush();
            } else {
                foreach ($ListeadminExiste as $adminExiste) {

                    $adminExiste->setIdOffreEmploi($idOffreEmploi);
                    $EntityManager->flush();
                }
            }

            $this->notify_creation->notifyOffreEmploi($SocieteEmail, $l->getLogin(), $idOffreEmploi, $offreEmploiRepository);
        }
    }



    public function OffreStageToAdmin(AdminRepository $adminRepository, $idOffreStage,$SocieteEmail,OffreStageRepository $offreStageRepository,AdminEmploiRepository $adminEmploiRepository)
    {
        $list = $adminRepository->findBy(array('type'=>'Admin des emplois', 'etat'=>1));
        $EntityManager = $this->getDoctrine()->getManager();
        foreach ($list as $l)
        {
            $ListeadminExiste = $adminEmploiRepository->findBy(array('id_A_E'=>$l->getId(),'id_Offre_Stage'=>null));
            if ($ListeadminExiste == null) {
                $AdminEmploi = new AdminEmploi();
                $AdminEmploi->setIdAE($l->getId());
                $AdminEmploi->setIdOffreStage($idOffreStage);
                $EntityManager->persist($AdminEmploi);
                $EntityManager->flush();
            } else {
            }
            foreach ($ListeadminExiste as $adminExiste) {



                    $adminExiste->setIdOffreStage($idOffreStage);
                    $EntityManager->flush();
                }


            $this->notify_creation->notifyOffreStage($SocieteEmail,$l->getLogin(),$idOffreStage,$offreStageRepository);
        }

    }

    /**
     * @param OffreEmploiRepository $offreEmploiRepository
     * @param AdminRepository $adminRepository
     * @param AdminEmploiRepository $adminEmploiRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/deactivateOffreEmploi/{id}", name="deactivateOffreEmploi", methods={"GET","POST"})
     */
    public function DeactivateOffreEmploi(OffreEmploiRepository $offreEmploiRepository, AdminRepository $adminRepository,AdminEmploiRepository $adminEmploiRepository,$id,Request $request): Response
    {
        $OffreEmploi=$offreEmploiRepository->find($id);
        $OffreEmploi->setEtat(0);
        $em=$this->getDoctrine()->getManager();




        $admins = $adminRepository->findBy(array('type'=>'Admin des emplois'));

        foreach ($admins as $admin)
        {
            $adminRepository->find($admin->getId())->setNonapprouve($admin->getNonapprouve()-1);

        }


        $adminAModifier = $adminEmploiRepository->findBy(array('id_Offre_Emploi'=>$id));
        //dd($adminASupprimer);
        foreach ($adminAModifier as $a) {
            $a->setIdOffreEmploi(null);
        }


        $em->remove($OffreEmploi);
        $em->flush();
        return $this->redirectToRoute('admin_emploi');

    }

    /**
     * @param OffreEmploiRepository $offreEmploiRepository
     * @param AdminRepository $adminRepository
     * @param AdminEmploiRepository $adminEmploiRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/activateOffreEmploi/{id}", name="activateOffreEmploi", methods={"GET","POST"})
     */
    public function ActivateOffreEmploi(OffreEmploiRepository $offreEmploiRepository, AdminRepository $adminRepository,AdminEmploiRepository $adminEmploiRepository,$id,Request $request): Response
    {
        $OffreEmploi=$offreEmploiRepository->find($id);
        $OffreEmploi->setEtat(1);
        $em=$this->getDoctrine()->getManager();


        $admins = $adminRepository->findBy(array('type'=>'Admin des emplois'));

        foreach ($admins as $admin)
        {
            $adminRepository->find($admin->getId())->setApprouve($admin->getApprouve()+1);
            $adminRepository->find($admin->getId())->setNonapprouve($admin->getNonapprouve()-1);

        }




        $em->flush();
        return $this->redirectToRoute('admin_emploi');

    }


    /**
     * @param OffreStageRepository $offreStageRepository
     * @param AdminRepository $adminRepository
     * @param AdminEmploiRepository $adminEmploiRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/deactivateOffreStage/{id}", name="deactivateOffreStage", methods={"GET","POST"})
     */
    public function DeactivateOffreStage(OffreStageRepository $offreStageRepository, AdminRepository $adminRepository,AdminEmploiRepository $adminEmploiRepository,$id,Request $request): Response
    {
        $OffreStage=$offreStageRepository->find($id);
        $OffreStage->setEtat(0);
        $em=$this->getDoctrine()->getManager();




        $admins = $adminRepository->findBy(array('type'=>'Admin des emplois'));

        foreach ($admins as $admin)
        {
            $adminRepository->find($admin->getId())->setNonapprouve($admin->getNonapprouve()-1);

        }


        $adminAModifier = $adminEmploiRepository->findBy(array('id_Offre_Stage'=>$id));
        //dd($adminASupprimer);
        foreach ($adminAModifier as $a) {
            $a->setIdOffreStage(null);
        }


        $em->remove($OffreStage);
        $em->flush();
        return $this->redirectToRoute('admin_emploi');

    }

    /**
     * @param OffreStageRepository $offreStageRepository
     * @param AdminRepository $adminRepository
     * @param AdminEmploiRepository $adminEmploiRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/activateOffreStage/{id}", name="activateOffreStage", methods={"GET","POST"})
     */
    public function ActivateOffreStage(OffreStageRepository $offreStageRepository, AdminRepository $adminRepository,AdminEmploiRepository $adminEmploiRepository,$id,Request $request): Response
    {
        $OffreStage=$offreStageRepository->find($id);
        $OffreStage->setEtat(1);
        $em=$this->getDoctrine()->getManager();


        $admins = $adminRepository->findBy(array('type'=>'Admin des emplois'));

        foreach ($admins as $admin)
        {
            $adminRepository->find($admin->getId())->setApprouve($admin->getApprouve()+1);
            $adminRepository->find($admin->getId())->setNonapprouve($admin->getNonapprouve()-1);

        }




        $em->flush();
        return $this->redirectToRoute('admin_emploi');

    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param OffreEmploiRepository $offreEmploiRepository
     * @param OffreStageRepository $offreStageRepository
     * @param AdminEmploiRepository $adminEmploiRepository
     * @param AdminRepository $adminRepository
     * @return Response
     * @Route ("/AllEmploi" , name="AllEmploi")
     */
    public function showEmploi(Request $request,PaginatorInterface $paginator,OffreEmploiRepository $offreEmploiRepository,OffreStageRepository $offreStageRepository,AdminEmploiRepository $adminEmploiRepository,AdminRepository $adminRepository){
        //$session = $request->getSession();
        //$id = $session->get('id');
        $id=23;
        $admin = $adminRepository->find($id);
        $ListeOffre = $adminEmploiRepository->findBy(array('id_A_E'=>$id));
        $AllOffreEmploi = [];
        $i=0;
        foreach ($ListeOffre as $offreEmploi){
            $OffreEmploi = $offreEmploiRepository->findBy(array('id'=>$offreEmploi->getIdOffreEmploi(),'etat'=>1));
            foreach ($OffreEmploi as $r){
                $AllOffreEmploi[$i] = $r;
                $i+=1;
            }
        }
        $AllOffreStage = [];
        $j=0;
        foreach ($ListeOffre as $offreStage){
            $OffreStage = $offreStageRepository->findBy(array('id'=>$offreStage->getIdOffreStage(),'etat'=>1));
            foreach ($OffreStage as $r){
                $AllOffreStage[$j] = $r;
                $j+=1;
            }
        }

        $AllOffreEmploi = $paginator->paginate(
        // Doctrine Query, not results
            $AllOffreEmploi,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        $AllOffreStage = $paginator->paginate(
        // Doctrine Query, not results
            $AllOffreStage,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('Admin_emploi/ShowDoneEmploi.html.twig',['ListeOffreEmploi'=>$AllOffreEmploi,'ListeOffreStage'=>$AllOffreStage,'admin'=>$admin]);
    }

}
