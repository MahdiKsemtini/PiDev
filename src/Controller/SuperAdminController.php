<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\AdminEmploi;
use App\Entity\AdminEvent;
use App\Entity\AdminReclamtion;
use App\Entity\Reclamation;
use App\Form\AdminFormType;
use App\Repository\AdminEmploiRepository;
use App\Repository\AdminEventRepository;
use App\Repository\EventLoisirRepository;
use App\Repository\FormationRepository;
use App\Repository\OffreEmploiRepository;
use App\Repository\OffreStageRepository;
use App\Repository\ReclamationRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdminRepository;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class SuperAdminController extends AbstractController
{
    /**
     * @Route("/super/admin", name="super_admin")
     */
    public function index(Request $request): Response
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
            elseif ($session->get('type')=='Admin des reclamations'){
                return $this->redirectToRoute('admin_reclamation');
            }
        }
        $random = random_int(1, 10);
        $em=$this->getDoctrine()->getRepository(Admin::class);
        $list= $em->findAll();

        $adminReclamations = $em->findBy(array('type'=>'Admin des reclamations'));
        $adminPubsEvents = $em->findBy(array('type'=>'Admin des events'));
        $adminEmplois = $em->findBy(array('type'=>'Admin des emplois'));
        return $this->render('super_admin/index.html.twig', [ 'list'=>$list , 'random'=>$random,
            'adminReclamations'=>$adminReclamations,'adminPubsEvents'=>$adminPubsEvents, 'adminEmplois'=>$adminEmplois]);
    }

    /**
     * @Route("/super/admin/CreateAdmin", name="CreateAdmin")
     * @param Request $request
     * @param ReclamationRepository $reclamationRepository
     * @param OffreStageRepository $offreStageRepository
     * @param OffreEmploiRepository $offreEmploiRepository
     * @param EventLoisirRepository $eventLoisirRepository
     * @param FormationRepository $formationRepository
     * @param AdminEmploiRepository $adminEmploiRepository
     * @param AdminEventRepository $adminEventRepository
     * @return RedirectResponse|Response
     */
    public function ajouterAdmin(Request $request , ReclamationRepository $reclamationRepository,OffreStageRepository $offreStageRepository,OffreEmploiRepository $offreEmploiRepository,EventLoisirRepository $eventLoisirRepository,FormationRepository $formationRepository,AdminEmploiRepository $adminEmploiRepository,AdminEventRepository $adminEventRepository,AdminRepository $adminRepository)
    {
        $admin = new Admin();
        $form = $this->createForm(AdminFormType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            if ($form->getData()->getEtat() == 'Active')
            {
                $admin->setEtat(1);
            }
            else
            {
                $admin->setEtat(0);
            }
            $em->persist($admin);
            $em->flush();
            $adminId=$adminRepository->findOneBy(['login' => $form->getData()->getLogin()]);
            //Count nobre de reclamation non approuve
            if ($form->getData()->getType() == 'Admin des reclamations'){
                $nb = $reclamationRepository->countReclamtionNonApprouve();
                foreach ($nb as $count) {
                    $admin->setNonapprouve((integer)$count['count']);
                    if($count['count']!=0){
                        $ListeRec = $reclamationRepository->findBy(array('etat'=>0));
                        foreach ($ListeRec as $item) {
                            $adminRec = new AdminReclamtion();
                            $adminRec->setIdAR($adminId->getId());
                            $adminRec->setIdReclamation($item->getId());
                            $em->persist($adminRec);
                            $em->flush();
                        }
                    }
                }

            }

            //Count de nombre d'offre d'emploi et de stage non approuvé
            if ($form->getData()->getType() == 'Admin des emplois'){
                $count1 = 0;
                $count2 = 0;
                $nbOffreEmploi = $offreEmploiRepository->countOffreEmploiNonApprouve();
                foreach ($nbOffreEmploi as $countOffreEmploi) {
                    $count1 = (integer)$countOffreEmploi['count'];
                }
                $nbOffreStage = $offreStageRepository->countOffreStageNonApprouve();
                foreach ($nbOffreStage as $countOffreStage) {
                    $count2 = (integer)$countOffreStage['count'];
                }
                $ListeOffreEmploi = $offreEmploiRepository->findBy(array('etat'=>0));
                $ListeOffreStage = $offreStageRepository->findBy(array('etat'=>0));

                if($count1!=0) {

                    if ($count1 >= $count2) {
                        foreach ($ListeOffreEmploi as $item) {
                            $adminEmploi = new AdminEmploi();
                            $adminEmploi->setIdAE($adminId->getId());
                            $adminEmploi->setIdOffreEmploi($item->getId());
                            $em->persist($adminEmploi);
                            $em->flush();
                        }
                        if ($count2 != 0) {
                            $ListeadminEmploi = $adminEmploiRepository->findBy(array('id_A_E' => $adminId->getId(), 'id_Offre_Stage' => null));
                            $i = 0;
                            foreach ($ListeadminEmploi as $item) {
                                if ($i < $count2) {
                                    $item->setIdOffreStage($ListeOffreStage[$i]->getId());
                                    $i += 1;
                                    $em->flush();
                                }
                            }
                        }
                    }
                }
                if($count2!=0){

                    if($count2>=$count1){
                        foreach ($ListeOffreStage as $item){
                            $adminEmploi = new AdminEmploi();
                            $adminEmploi->setIdAE($adminId->getId());
                            $adminEmploi->setIdOffreStage($item->getId());
                            $em->persist($adminEmploi);
                            $em->flush();
                        }
                        if($count1 !=0){
                            $ListeadminEmploi = $adminEmploiRepository->findBy(array('id_A_E'=>$adminId->getId(),'id_Offre_Emploi'=>null));
                            $i=0;
                            foreach ($ListeadminEmploi as $item){
                                if($i<$count1) {
                                    $item->setIdOffreEmploi($ListeOffreEmploi[$i]->getId());
                                    $i += 1;
                                    $em->flush();
                                }
                            }
                        }
                    }
                }




                $nbOffre=$count1+$count2;
                $admin->setNonapprouve($nbOffre);
            }

            //Count de nombre fe formation et evenements non approuve
            if ($form->getData()->getType() == 'Admin des events'){
                $count3 = 0;
                $count4 = 0;
                $nbEventLoisir = $eventLoisirRepository->countEventLoisirNonApprouve();
                foreach ($nbEventLoisir as $countEventLoisir) {
                    $count3 = (integer)$countEventLoisir['count'];
                }
                $nbFormation = $formationRepository->countFormationNonApprouve();
                foreach ($nbFormation as $countFormation) {
                    $count4 = (integer)$countFormation['count'];
                }

                $ListeEventLoisir = $eventLoisirRepository->findBy(array('Etat'=>0));
                $ListeFormation = $formationRepository->findBy(array('Etat'=>0));

                if($count3!=0) {

                    if ($count3 >= $count4) {
                        foreach ($ListeEventLoisir as $item) {
                            $adminEvent = new AdminEvent();
                            $adminEvent->setIdAE($adminId->getId());
                            $adminEvent->setIdEventLoisir($item->getId());
                            $em->persist($adminEvent);
                            $em->flush();
                        }
                        if ($count4 != 0) {
                            $ListeadminEmploi = $adminEventRepository->findBy(array('id_A_E' => $adminId->getId(), 'id_Formation' => null));
                            $i = 0;
                            foreach ($ListeadminEmploi as $item) {
                                if ($i < $count4) {
                                    $item->setIdFormation($ListeFormation[$i]->getId());
                                    $i += 1;
                                    $em->flush();
                                }
                            }
                        }
                    }
                }
                if($count4!=0){

                    if($count4>=$count3){
                        foreach ($ListeFormation as $item){
                            $adminEvent = new AdminEvent();
                            $adminEvent->setIdAE($adminId->getId());
                            $adminEvent->setIdFormation($item->getId());
                            $em->persist($adminEvent);
                            $em->flush();
                        }
                        if($count3 !=0){
                            $ListeadminEmploi = $adminEventRepository->findBy(array('id_A_E'=>$adminId->getId(),'id_Event_Loisir'=>null));
                            $i=0;
                            foreach ($ListeadminEmploi as $item){
                                if($i<$count3) {
                                    $item->setIdEventLoisir($ListeEventLoisir[$i]->getId());
                                    $i += 1;
                                    $em->flush();
                                }
                            }
                        }
                    }
                }

                $nbEvent=$count3+$count4;
                $admin->setNonapprouve($nbEvent);
                $em->flush();

            }

            $admin->setApprouve(0);
            $em->flush();

            return $this->redirectToRoute("super_admin");
        }
        return $this->render('super_admin/CreateAdmin.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @param $id
     * @Route ("/super/admin/ViewAdminProfile/{id}" , name="ViewAdminProfile")
     */
    public function AfficheAdmin($id)
    {
        $em=$this->getDoctrine()->getManager();
        $admin =$em->getRepository(Admin::class)->find($id);
        return $this->render('super_admin/ViewAdminProfile.html.twig',['admin' =>$admin]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     * @Route ("/super/admin/UpdateAdmin/{id}" , name="UpdateAdmin")
     */
    public function UpdateAdmin(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $admin =$em->getRepository(Admin::class)->find($id);
        $form = $this->createForm(AdminFormType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $etat = $form->get('etat')->getData();
            if ($etat == 'Active')
            {
                $admin->setEtat(1);
            }
            else
            {
                $admin->setEtat(0);
            }

            $em->flush();
            return $this->redirectToRoute("ViewAdminProfile", array('id'=>$id));
        }
        return $this->render('super_admin/UpdateAdmin.html.twig',['form' => $form->createView()]);

    }


    /**
     * @param $id
     * @return RedirectResponse
     * @Route ("/super/admin/DeleteAdmin/{id}" , name="DeleteAdmin")
     */
    public function deleteAdmin($id)
    {
        $em = $this->getDoctrine()->getManager();
        $find = $em->getRepository(Admin::class)->find($id);
        $em->remove($find);
        $em->flush();
        return $this->redirectToRoute("super_admin");

    }

    /**
     * @Route("/searchAdmin ", name="searchAdmin")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @param AdminRepository $adminRepository
     * @return Response
     * @throws ExceptionInterface
     */
    public function searchAdmin(Request $request,NormalizerInterface $Normalizer,AdminRepository $adminRepository)
    {
        $requestString=$request->get('searchValue');
        $admin = $adminRepository->findAdminParPrenom($requestString);
        $jsonContent = $Normalizer->normalize($admin, 'json',['groups'=>'admin:read']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }
}