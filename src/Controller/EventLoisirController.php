<?php

namespace App\Controller;

use App\Entity\EventLoisir;
use App\Entity\Formation;
use App\Form\EventLoisirType;
use App\Form\FormationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/AfficherEvent/{id}", name="AfficherEvent")
     */
    public function AfficherFormation($id){
        $em=$this->getDoctrine()->getRepository(EventLoisir::class);
        $events=$em->findBy(array('idU' => $id,'Etat'=>1));
        $events1=$em->findBy(array('Etat'=>1));
        return $this->render("event_loisir/AfficherEventLoisir.html.twig",['events'=>$events,'evenement'=>$events1]);
    }

    /**
     * @Route("/addEvent",name="addEvent")
     */
    public function addFormation(\Symfony\Component\HttpFoundation\Request $request){
        $event=new EventLoisir();
        $form=$this->createForm(EventLoisirType::class,$event);
        $form->handleRequest($request);
        if(($form->isSubmitted()) and ($form->isValid())){
            $event->setIdU(0);
            $event->setEtat(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
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
            $event->setIdU(0);
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
        $e=$em->getRepository(EventLoisir::class)->find($id);
        $em->remove($e);
        $em->flush();
        return $this->redirectToRoute("AfficherEvent",array('id'=>0));
    }

}
