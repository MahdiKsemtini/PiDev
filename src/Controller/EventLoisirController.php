<?php

namespace App\Controller;

use App\Entity\EventLoisir;
use App\Entity\Formation;
use App\Entity\Freelancer;
use App\Entity\Participant;
use App\Entity\Societe;
use App\Form\EventLoisirType;
use App\Repository\AdminEventRepository;
use App\Repository\AdminRepository;
use App\Repository\EventLoisirRepository;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class EventLoisirController extends AbstractController
{
    /**
     * @Route("/event/loisir", name="event_loisir")
     */
    public function index(): Response
    {
        return $this->render('event_loisir/index.html.twig', [
            'controller_name' => 'EventLoisirController',
        ]);
    }

    /**
     * @Route("/addEvent",name="addEvent")
     */
    public function addevent(\Symfony\Component\HttpFoundation\Request $request,AdminRepository $adminRepository,AdminEventController $adminEventController,EventLoisirRepository $eventLoisirRepository,AdminEventRepository $adminEventRepository){
        $event=new EventLoisir();
        $em=$this->getDoctrine()->getManager();
        $emfr=$this->getDoctrine()->getRepository(Freelancer::class);
        $form=$this->createForm(EventLoisirType::class,$event);
        $form->handleRequest($request);
        if(($form->isSubmitted()) and ($form->isValid())){

            $freelancer=$emfr->find(1);
            $em->merge($freelancer);
            $em->flush($freelancer);
            $event->setIdFr($freelancer);
            $event->setEtat(0);

            $admins = $adminRepository->findBy(array('type'=>'Admin des evenements'));
            foreach ($admins as $admin) {
                $admin->setNonapprouve($admin->getNonapprouve() + 1);

            }

            $em->persist($event);
            $em->flush();

            $freelanceremail = $freelancer->getEmail();
            $adminEventController->EventLoisirToAdmin($adminRepository,$event->getId(),$freelanceremail->getEmail(),$eventLoisirRepository,$adminEventRepository);


            $this->addFlash('msg',"Evenement ajouté avec succées");
            return $this->redirectToRoute("AfficherEvent",array('id'=>0));
        }
        return $this->render("event_loisir/AjouterEvent.html.twig",['f'=>$form->createView()]);

    }

    /**
     * @Route("/triEventDateC",name="triEventDateC")
     */
    public function orderByDateC(EventLoisirRepository $repository,Request $request){
        $type=$request->get("type");
        $idu=$request->get("idu");
        $eventC=$repository->OrderByDateC();
        $em=$this->getDoctrine()->getRepository(EventLoisir::class);

        if($type=="freelancer"){
            $events=$em->findBy(array('idFr' => $idu,'Etat'=>1));
            $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idF'=>$idu,'typeE'=>'evenement'));
            $formparticipation=array();
            for($i=0;$i<sizeof($participation);$i++){
                $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdE()->getId());
            }

        }
        else {
            $events = $em->findBy(array('idSo' => $idu, 'Etat' => 1));
            $participation = $this->getDoctrine()->getRepository(Participant::class)->findBy(array('idS' => $idu, 'typeE' => 'evenement'));
            $formparticipation = array();
            for ($i = 0; $i < sizeof($participation); $i++) {
                $formparticipation[sizeof($formparticipation)] = $em->find($participation[$i]->getIdE()->getId());
            }
        }
        return $this->render("event_loisir/AfficherEventLoisirTri.html.twig",['events'=>$events,'evenement'=>$eventC,'participation'=>$formparticipation]);


    }
    /**
     * @Route("/triEventDateD",name="triEventDateD")
     */
    public function orderByDateD(EventLoisirRepository  $repository,Request $request){
        $type=$request->get("type");
        $idu=$request->get("idu");
        $eventD=$repository->OrderByDateD();
        $em=$this->getDoctrine()->getRepository(EventLoisir::class);

        if($type=="freelancer"){
            $events=$em->findBy(array('idFr' => $idu,'Etat'=>1));
            $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idF'=>$idu,'typeE'=>'evenement'));
            $formparticipation=array();
            for($i=0;$i<sizeof($participation);$i++){
                $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdE()->getId());
            }

        }
        else {
            $events = $em->findBy(array('idSo' => $idu, 'Etat' => 1));
            $participation = $this->getDoctrine()->getRepository(Participant::class)->findBy(array('idS' => $idu, 'typeE' => 'evenement'));
            $formparticipation = array();
            for ($i = 0; $i < sizeof($participation); $i++) {
                $formparticipation[sizeof($formparticipation)] = $em->find($participation[$i]->getIdE()->getId());
            }
        }
        return $this->render("event_loisir/AfficherEventLoisirTri.html.twig",['events'=>$events,'evenement'=>$eventD,'participation'=>$formparticipation]);
    }


    /**
     * @Route("/mapE/{id}", name="mapE"))
     */
    public function map($id){
        $em=$this->getDoctrine()->getRepository(EventLoisir::class);
        $event=$em->find($id);
        return $this->render('event_loisir/EventMAP.html.twig', [
            'e'=>$event

        ]);

    }


}