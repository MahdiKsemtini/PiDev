<?php

namespace App\Controller;

use App\Entity\AdminReclamtion;
use App\Entity\Freelancer;
use App\Repository\AdminEmploiRepository;
use App\Repository\AdminReclamtionRepository;
use App\Repository\AdminRepository;
use App\Repository\ReclamationRepository;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Notifications\CreationCompteNotification;


class AdminReclamationController extends AbstractController
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
     * @Route("/admin/reclamation", name="admin_reclamation")
     * @param ReclamationRepository $reclamationRepository
     * @param Request $request
     * @return Response
     */
    public function index(ReclamationRepository $reclamationRepository, Request $request): Response
    {
        $session = $request->getSession();

        if($session->get('id')!=null){

            if($session->get('compte_facebook')!=null){
                return $this->redirectToRoute('forum');
            }
            elseif($session->get('status_juridique')!=null){
                return $this->redirectToRoute('forum');
            }
            elseif ($session->get('type')=='Admin des emplois'){
                return $this->redirectToRoute('admin_emploi');
            }
            elseif ($session->get('type')=='Admin des events'){
                return $this->redirectToRoute('admin_pub_event');
            }
            elseif ($session->get('type')=='super admin'){
                return $this->redirectToRoute('super_admin');
            }
        }
        $listReclamation=$reclamationRepository->findAll();
        return $this->render('admin_reclamation/index.html.twig', [
            'listeReclamation' => $listReclamation
        ]);
    }

    /**
     * @param ReclamationRepository $reclamationRepository
     * @param AdminRepository $adminRepository
     * @param AdminReclamtionRepository $adminReclamtionRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/deactivateReclamation/{id}", name="deactivateReclamation", methods={"GET","POST"})
     */
    public function Deactivate(ReclamationRepository $reclamationRepository, AdminRepository $adminRepository,AdminReclamtionRepository $adminReclamtionRepository,$id,Request $request): Response
    {
        $reclamation=$reclamationRepository->find($id);
        $reclamation->setEtat(0);
        $em=$this->getDoctrine()->getManager();




        $admins = $adminRepository->findBy(array('type'=>'Admin des reclamations'));

        foreach ($admins as $admin)
        {
            $adminRepository->find($admin->getId())->setNonapprouve($admin->getNonapprouve()-1);

        }


        $adminASupprimer = $adminReclamtionRepository->findBy(array('id_Reclamation'=>$id));
        //dd($adminASupprimer);
        foreach ($adminASupprimer as $a) {
            $em->remove($a);
        }


        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute('admin_reclamation');

    }

    /**
     * @param ReclamationRepository $reclamationRepository
     * @param AdminRepository $adminRepository
     * @param AdminEmploiRepository $adminEmploiRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/activateReclamation/{id}", name="activateReclamation", methods={"GET","POST"})
     */
    public function Activate(ReclamationRepository $reclamationRepository,AdminRepository $adminRepository,AdminReclamtionRepository $adminReclamtionRepository,$id,Request $request): Response
    {
        $session = $request->getSession();
        $reclamation=$reclamationRepository->find($id);
        $reclamation->setEtat(1);
        $em=$this->getDoctrine()->getManager();
        $admins = $adminRepository->findBy(array('type'=>'Admin des reclamations'));

        foreach ($admins as $admin)
        {

            $adminRepository->find($admin->getId())->setNonapprouve($admin->getNonapprouve()-1);

        }
        $adminRepository->find($session->get('id'))->setApprouve($adminRepository->find($session->get('id'))->getApprouve()+1);

        /*$adminASupprimer = $adminReclamtionRepository->findBy(array('id_Reclamation'=>$id));
        foreach ($adminASupprimer as $a) {
            $em->remove($a);
            $em->flush();
        }*/


        $em->flush();
        return $this->redirectToRoute('admin_reclamation');

    }



    public function ReclamationToAdmin(AdminRepository $adminRepository , $IdReclamtion , Freelancer $freelancer,ReclamationRepository $reclamationRepository)
    {
        $list = $adminRepository->findBy(array('type'=>'Admin des reclamations', 'etat'=>1));

        $em = $this->getDoctrine()->getManager();
        foreach ($list as $l)
        {
            $id =$l->getId();
            $adminReclamation = new AdminReclamtion();
            $adminReclamation->setIdAR($id);
            $adminReclamation->setIdReclamation($IdReclamtion);
            $em->persist($adminReclamation);
            $em->flush();

            $this->notify_creation->notifyReclamation($freelancer->getEmail(),$l->getLogin(),$IdReclamtion,$reclamationRepository);


            /*$email = (new NotificationEmail())
                ->from($freelancer->getEmail())
                ->to($l->getLogin())
                ->subject('Nouvelle Reclamtion detecté')
                ->markdown('
            Bonjour Mr {{$l->getNom()}}
            Une nouvelle reclamation est envoyé de la part de {{$freelancer->getNom()}}
            ' )
                ->action('More info?', '/admin/reclamation')
                ->importance(NotificationEmail::IMPORTANCE_HIGH)
            ;*/
        }
    }

    /**
     * @param ReclamationRepository $reclamationRepository
     * @param AdminReclamtionRepository $adminReclamtionRepository
     * @Route ("/AllReclamation" , name="AllReclamation")
     */
    public function showReclamation(ReclamationRepository $reclamationRepository,AdminReclamtionRepository $adminReclamtionRepository,AdminRepository $adminRepository,Request $request){
        $session = $request->getSession();
        $id = $session->get('id');
        $admin = $adminRepository->find($id);
        $ListeReclamation = $adminReclamtionRepository->findBy(array('id_A_R'=>$id));
        $AllReclamation = [];
        $i=0;
        foreach ($ListeReclamation as $Reclamation){
            $Rec = $reclamationRepository->findBy(array('id'=>$Reclamation->getIdReclamation(),'etat'=>1));
            foreach ($Rec as $r){
                $AllReclamation[$i] = $r;
                $i+=1;
            }
        }
       return $this->render('Admin_reclamation/ShowDoneReclamation.html.twig',['ListReclamations'=>$AllReclamation,'admin'=>$admin]);
    }
}