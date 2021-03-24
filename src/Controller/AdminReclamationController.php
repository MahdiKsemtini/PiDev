<?php

namespace App\Controller;

use App\Entity\AdminReclamation;
use App\Entity\Freelancer;
use App\Entity\Reclamation;
use App\Notifications\CreationCompteNotification;
use App\Repository\AdminReclamationRepository;
use App\Repository\AdminRepository;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    /*public function index(ReclamationRepository $repository): Response
    {
        $list=$repository->findAll();
        return $this->render('admin_reclamation/index.html.twig', [
            'controller_name' => 'AdminReclamationController',
            'list'=>$list,
        ]);
    }*/

    /**
     * @Route("/admin/reclamation", name="admin_reclamation")
     */
    public function index(AdminReclamationRepository $adminReclamationRepository, ReclamationRepository $reclamationRepository): Response
    {

        /*$list = $adminReclamationRepository->findBy(array('id_A_R'=> 1));


        foreach ($list as $l)
        {
            $id = $l->getIdReclamation();
            $listReclamation = $reclamationRepository->find($id);
        }*/
        $listReclamation=$reclamationRepository->findAll();
        return $this->render('admin_reclamation/index.html.twig', [

            'listeReclamation'=>$listReclamation
        ]);
    }

    /**
     * @param Request $request
     * @Route("/deactivateReclamation/{id}", name="deactivateReclamation", methods={"GET","POST"})
     */
    public function Deactivate(ReclamationRepository $reclamationRepository,$id,Request $request): Response
    {
        $societe=$reclamationRepository->find($id);
        $societe->setEtat(0);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('admin_reclamation');

    }

    /**
     * @param Request $request
     * @Route("/activateReclamation/{id}", name="activateReclamation", methods={"GET","POST"})
     */
    public function Activate(ReclamationRepository $reclamationRepository,$id,Request $request): Response
    {
        $reclamation=$reclamationRepository->find($id);
        $reclamation->setEtat(1);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('admin_reclamation');

    }

    public function ReclamationToAdmin(AdminRepository $adminRepository , $IdReclamtion, Freelancer $freelancer)
    {
        $list = $adminRepository->findBy(array('type'=>'Admin des reclamations', 'etat'=>1));

        $em = $this->getDoctrine()->getManager();
        foreach ($list as $l)
        {
            $id =$l->getId();
            $adminReclamation = new AdminReclamation();
            $adminReclamation->setIdAR($id);
            $adminReclamation->setIdReclamation($IdReclamtion);
            $em->persist($adminReclamation);
            $em->flush();
            $this->notify_creation->notify($freelancer->getEmail(),$l->getLogin());
        }
    }
}
