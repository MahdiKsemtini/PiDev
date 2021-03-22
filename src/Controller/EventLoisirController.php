<?php

namespace App\Controller;

use App\Entity\EventLoisir;
use App\Entity\Formation;
use App\Entity\Freelancer;
use App\Entity\Participant;
use App\Form\EventLoisirType;
use App\Form\FormationType;
use App\Form\SearchType;
use App\Form\SearchTypeE;
use App\Repository\EventLoisirRepository;
use App\Repository\FormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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
     * @Route("/AfficherEvent", name="AfficherEvent")
     */
    public function AfficherFormation(Request $request){
        $type=$request->get("type");
        $idu=$request->get("idu");
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

        $events1=$em->findBy(array('Etat'=>1));
        return $this->render("event_loisir/AfficherEventLoisir.html.twig",['events'=>$events,'evenement'=>$events1,'participation'=>$formparticipation]);
    }

    /**
     * @Route("/addEvent",name="addEvent")
     */
    public function addevent(\Symfony\Component\HttpFoundation\Request $request){
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
            $event->setEtat(1);
            $event->setLng(10.6405392);
            $event->setLat(35.8288175);

            $em->persist($event);
            $em->flush();
            $this->addFlash('msg',"Evenement ajouté avec succées");
            return $this->redirectToRoute("AfficherEvent",array('id'=>0));
        }
        return $this->render("event_loisir/AjouterEvent.html.twig",['f'=>$form->createView()]);

    }

    /**
     * @Route("/ModifierEvent/{id}",name="ModifierEvent")
     */
    public function updateEvent($id,\Symfony\Component\HttpFoundation\Request $request){
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository(EventLoisir::class)->find($id);
        $form=$this->createForm(EventLoisirType::class,$event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->flush();
            return $this->redirectToRoute("AfficherEvent",array('id'=>0));
        }
        return $this->render("event_loisir/AjouterEvent.html.twig",['f'=>$form->createView()]);


    }
    /**
     * @Route("/deleteEvent/{id}",name="deleteEvent")
     */
    public function deleteEvent($id){
        $em=$this->getDoctrine()->getManager();
        $part=$em->getRepository(Participant::class)->findBy(array('idFO'=>$id));
        if($part!=null){
            $this->addFlash('info',"Cet evenement ne peut pas étre supprimé car elle contient deja des participants;Veuillez contacter l'administrateur pour la supprimer");
        }
        else {
            $e = $em->getRepository(EventLoisir::class)->find($id);
            $em->remove($e);
            $em->flush();
        }
        return $this->redirectToRoute("AfficherEvent",array('idu'=>1,'type'=>"freelancer"));
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
     * @Route("/ModifierEventBack/{id}",name="ModifierEventBack")
     */
    public function updateEventBack($id,\Symfony\Component\HttpFoundation\Request $request){
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository(EventLoisir::class)->find($id);
        $form=$this->createForm(EventLoisirType::class,$event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->flush();
            return $this->redirectToRoute("Afficherallformationback",array('id'=>0));
        }
        return $this->render("event_loisir/AjouterEvent.html.twig",['f'=>$form->createView()]);


    }

    /**
     * @Route("/deleteEventBack/{id}",name="deleteEventBack")
     */
    public function deleteEventBack($id){
        $em=$this->getDoctrine()->getManager();
        $e=$em->getRepository(EventLoisir::class)->find($id);
        $em->remove($e);
        $em->flush();
        return $this->redirectToRoute("Afficherallformationback",array('id'=>0));
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
    /**
     * @Route("/searchEvent", name="searchEvent")
     */
    public function searchEvent(Request $request,NormalizerInterface $Normalizer,EventLoisirRepository $repository)
    {

        $requestString=$request->get('searchValue');
        $events = $repository->search($requestString);
        $jsonContent = $Normalizer->normalize($events, 'json',['groups'=>'event:read']);
        $retour=json_encode($jsonContent);

        return new Response($retour);

    }


}
