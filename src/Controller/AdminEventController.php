<?php

namespace App\Controller;

use App\Entity\AdminEvent;
use App\Entity\EventLoisir;
use App\Entity\Freelancer;
use App\Entity\Participant;
use App\Entity\Societe;
use App\Notifications\CreationCompteNotification;
use App\Repository\AdminEventRepository;
use App\Repository\AdminRepository;
use App\Repository\EventLoisirRepository;
use App\Repository\FormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEventController extends AbstractController
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
     * @Route("/admin/event", name="admin_event")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param EventLoisirRepository $eventLoisirRepository
     * @param FormationRepository $formationRepository
     * @return Response
     */
    public function index(PaginatorInterface $paginator,Request $request,EventLoisirRepository $eventLoisirRepository,FormationRepository $formationRepository): Response
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
            elseif ($session->get('type')=='super admin'){
                return $this->redirectToRoute('super_admin');
            }
        }

        $ListeEventLoisir = $eventLoisirRepository->findBy(array('Etat'=>0));
        $ListeFormation = $formationRepository->findBy(array('Etat'=>0));
        $ListeEventLoisir = $paginator->paginate(
        // Doctrine Query, not results
            $ListeEventLoisir,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        $ListeFormation = $paginator->paginate(
        // Doctrine Query, not results
            $ListeFormation,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('admin_event/index.html.twig', [
            'ListeEventLoisir'=>$ListeEventLoisir,
            'ListeFormation'=>$ListeFormation
        ]);
    }

    public function EventLoisirToAdmin(AdminRepository $adminRepository, $idEventLoisir,$FreelancerEmail,EventLoisirRepository $eventLoisirRepository,AdminEventRepository $adminEventRepository)
    {
        $list = $adminRepository->findBy(array('type' => 'Admin des events', 'etat' => 1));
        $EntityManager = $this->getDoctrine()->getManager();

        foreach ($list as $l) {
            $ListeadminExiste = $adminEventRepository->findOneBy(['id_A_E' => $l->getId(), 'id_Event_Loisir' => null]);
            if ($ListeadminExiste == null) {
                $AdminEmploi = new AdminEvent();
                $AdminEmploi->setIdAE($l->getId());
                $AdminEmploi->setIdEventLoisir($idEventLoisir);
                $EntityManager->persist($AdminEmploi);
                $EntityManager->flush();
            } else {
                foreach ($ListeadminExiste as $adminExiste) {

                    $adminExiste->setIdEventLoisir($idEventLoisir);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                }
            }

            $this->notify_creation->notifyEventLoisir($FreelancerEmail, $l->getLogin(), $idEventLoisir,$eventLoisirRepository);
        }
    }

    public function FormationToAdmin(AdminRepository $adminRepository, $idFormation,$FreelancerEmail,FormationRepository $formationRepository,AdminEventRepository $adminEventRepository)
    {
        $list = $adminRepository->findBy(array('type' => 'Admin des events', 'etat' => 1));
        $EntityManager = $this->getDoctrine()->getManager();

        foreach ($list as $l) {
            $ListeadminExiste = $adminEventRepository->findOneBy(['id_A_E' => $l->getId(), 'id_Formation' => null]);
            if ($ListeadminExiste == null) {
                $AdminEmploi = new AdminEvent();
                $AdminEmploi->setIdAE($l->getId());
                $AdminEmploi->setIdFormation($idFormation);
                $EntityManager->persist($AdminEmploi);
                $EntityManager->flush();
            } else {
                foreach ($ListeadminExiste as $adminExiste) {

                    $adminExiste->setIdFormation($idFormation);
                    $EntityManager->flush();
                }
            }

            $this->notify_creation->notifyFormation($FreelancerEmail, $l->getLogin(), $idFormation,$formationRepository);
        }
    }

    /**
     * @param \Swift_Mailer $mailer
     * @param EventLoisirRepository $eventLoisirRepository
     * @param AdminRepository $adminRepository
     * @param AdminEventRepository $adminEventRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/deactivateEvenement/{id}", name="deactivateEvenement", methods={"GET","POST"})
     */
    public function DeactivateEventLoisir(\Swift_Mailer $mailer, EventLoisirRepository $eventLoisirRepository, AdminRepository $adminRepository,AdminEventRepository $adminEventRepository,$id,Request $request): Response
    {
        $e = $eventLoisirRepository->find($id);
        $em=$this->getDoctrine()->getManager();
        $part=$em->getRepository(Participant::class)->findBy(array('idE'=>$id));
        if($part!=null) {
            for ($i = 0; $i < sizeof($part); $i++) {
                if ($part[$i]->getIdF() != null) {
                    $user = $em->getRepository(Freelancer::class)->find($part[$i]->getIdF());
                    $ev = $em->getRepository(Participant::class)->findOneBy(array('idE' => $id, 'idF' => $part[$i]->getIdF()));
                    $em->remove($ev);
                    $em->flush();
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('rightjob.inc@gmail.com')
                        ->setTo($user->getEmail())
                        ->setSubject('annulation du evenement')
                        ->setBody($this->renderView('formation/FormationMAILDelete.html.twig', ['user' => $user, 'e' => $e, 'type' => 'evenement']), 'text/html');
                    $mailer->send($message);
                } else {
                    $userS = $em->getRepository(Societe::class)->find($part[$i]->getIdS());
                    $e1 = $em->getRepository(Participant::class)->findOneBy(array('idE' => $id, 'idS' => $part[$i]->getIdS()));
                    $em->remove($e1);
                    $em->flush();
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('rightjob.inc@gmail.com')
                        ->setTo($userS->getEmail())
                        ->setSubject('annulation  evenement')
                        ->setBody($this->renderView('formation/FormationMAILDelete.html.twig', ['user' => $userS, 'e' => $e, 'type' => 'evenement']), 'text/html');
                    $mailer->send($message);
                }
            }
        }






        $admins = $adminRepository->findBy(array('type'=>'Admin des events'));

        foreach ($admins as $admin)
        {
            $admin->setNonapprouve($admin->getNonapprouve()-1);

        }


        $adminAModifier = $adminEventRepository->findBy(array('id_Event_Loisir'=>$id));
        //dd($adminASupprimer);
        foreach ($adminAModifier as $a) {
            $a->setIdEventLoisir(null);
        }


        $em->remove($e);
        $em->flush();
        return $this->redirectToRoute('admin_event');

    }

    /**
     * @param EventLoisirRepository $eventLoisirRepository
     * @param AdminRepository $adminRepository
     * @param AdminEventRepository $adminEventRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/activateEvenement/{id}", name="activateEvenement", methods={"GET","POST"})
     */
    public function ActivateEventLoisir(EventLoisirRepository $eventLoisirRepository, AdminRepository $adminRepository,AdminEventRepository $adminEventRepository,$id,Request $request): Response
    {
        $session = $request->getSession();

        $Event=$eventLoisirRepository->find($id);
        $Event->setEtat(1);
        $em=$this->getDoctrine()->getManager();


        $admins = $adminRepository->findBy(array('type'=>'Admin des events'));

        foreach ($admins as $admin)
        {
            $admin->setNonapprouve($admin->getNonapprouve()-1);

        }
        $adminRepository->find($session->get('id'))->setApprouve($adminRepository->find($session->get('id'))->getApprouve()+1);




        $em->flush();
        return $this->redirectToRoute('admin_event');

    }

    /**
     * @param FormationRepository $formationRepository
     * @param AdminRepository $adminRepository
     * @param AdminEventRepository $adminEventRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/deactivateFormation/{id}", name="deactivateFormation", methods={"GET","POST"})
     */
    public function DeactivateFormation(FormationRepository $formationRepository, AdminRepository $adminRepository,AdminEventRepository $adminEventRepository,$id,Request $request): Response
    {
        $Formation=$formationRepository->find($id);
        $Formation->setEtat(0);
        $em=$this->getDoctrine()->getManager();




        $admins = $adminRepository->findBy(array('type'=>'Admin des events'));

        foreach ($admins as $admin)
        {
            $admin->setNonapprouve($admin->getNonapprouve()-1);

        }


        $adminAModifier = $adminEventRepository->findBy(array('id_Formation'=>$id));
        //dd($adminASupprimer);
        foreach ($adminAModifier as $a) {
            $a->setIdFormation(null);
        }


        $em->remove($Formation);
        $em->flush();
        return $this->redirectToRoute('admin_event');

    }

    /**
     * @param FormationRepository $formationRepository
     * @param AdminRepository $adminRepository
     * @param AdminEventRepository $adminEventRepository
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/activateFormation/{id}", name="activateFormation", methods={"GET","POST"})
     */
    public function ActivateFormation(FormationRepository $formationRepository, AdminRepository $adminRepository,AdminEventRepository $adminEventRepository,$id,Request $request): Response
    {
        $session = $request->getSession();

        $formation=$formationRepository->find($id);
        $formation->setEtat(1);
        $em=$this->getDoctrine()->getManager();


        $admins = $adminRepository->findBy(array('type'=>'Admin des events'));

        foreach ($admins as $admin)
        {
            $admin->setNonapprouve($admin->getNonapprouve()-1);

        }
        $adminRepository->find($session->get('id'))->setApprouve($adminRepository->find($session->get('id'))->getApprouve()+1);




        $em->flush();
        return $this->redirectToRoute('admin_event');

    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param FormationRepository $formationRepository
     * @param EventLoisirRepository $eventLoisirRepository
     * @param AdminRepository $adminRepository
     * @param AdminEventRepository $adminEventRepository
     * @return Response
     * @Route ("/AllEvent" , name="AllEvent")
     */
    public function showEmploi(Request $request,PaginatorInterface $paginator,FormationRepository $formationRepository,EventLoisirRepository $eventLoisirRepository, AdminRepository $adminRepository,AdminEventRepository $adminEventRepository){
        $session = $request->getSession();
        $id = $session->get('id');

        $admin = $adminRepository->find($id);
        $ListeEvent = $adminEventRepository->findBy(array('id_A_E'=>$id));
        $AllEventLoisir = [];
        $i=0;
        foreach ($ListeEvent as $offreEventLoisir){
            $OffreEventLoisir = $eventLoisirRepository->findBy(array('id'=>$offreEventLoisir->getIdEventLoisir(),'Etat'=>1));
            foreach ($OffreEventLoisir as $r){
                $AllEventLoisir[$i] = $r;
                $i+=1;
            }
        }
        $AllFormation = [];
        $j=0;
        foreach ($ListeEvent as $formationStage){
            $FormationStage = $formationRepository->findBy(array('id'=>$formationStage->getIdFormation(),'Etat'=>1));
            foreach ($FormationStage as $r){
                $AllFormation[$j] = $r;
                $j+=1;
            }
        }

        $AllEventLoisir = $paginator->paginate(
        // Doctrine Query, not results
            $AllEventLoisir,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        $AllFormation = $paginator->paginate(
        // Doctrine Query, not results
            $AllFormation,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('Admin_event/ShowDoneEvent.html.twig',['ListeEventLoisir'=>$AllEventLoisir,'ListeFormation'=>$AllFormation,'admin'=>$admin]);
    }
}
