<?php

namespace App\Controller;

use App\Entity\EventLoisir;
use App\Entity\Formation;
use App\Entity\Freelancer;
use App\Entity\Participant;
use App\Entity\Societe;
use App\Form\EventLoisirType;
use App\Form\FormationType;
use App\Form\SearchType;
use App\Form\SearchTypeE;
use App\Repository\EventLoisirRepository;
use App\Repository\FormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
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
        $part=$em->getRepository(Participant::class)->findBy(array('idE'=>$id));
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
     * @Route("/addParticiperE",options={"expose"=true}, name="addParticiperE")
     */
    public function participer(\Symfony\Component\HttpFoundation\Request $request,MailerInterface $mailer,\Swift_Mailer $mailer1,\Twilio\Rest\Client $twilio){
        $typee="evenement";
        $typeu="societe";
        $idev=$request->get("idev");
        $ids=1;
        $idf=0;
        $num=$request->get("num");
        $typeV=$request->get("type");
        $typeC=$request->get("typeC");
        $emp=$this->getDoctrine()->getManager();
        $participant=new Participant();

        if($typeu=="societe"){
            $part=$emp->getRepository(Participant::class)->findBy(array('idS' => $ids,'idE'=>$idev));
            if($part!=null){
                $this->addFlash('alert',"Vous avez déja participer à cet evenement");
            }


        }
        else{
            $part=$emp->getRepository(Participant::class)->findBy(array('idF' => $idf,'idE'=>$idev));
            if($part!=null){
                $this->addFlash('alert',"Vous avez déja participer à cet evenement");
                }

        }

        if($part==null){
            if($typeu=="societe"){
                $s=$emp->getRepository(Societe::class)->find($ids);
                $user=$emp->getRepository(Societe::class)->find($ids);
                $emp->merge($s);
                $emp->flush($s);
                $participant->setIdS($s);
                $e=$emp->getRepository(EventLoisir::class)->find($idev);

                $e->setNbParticipant($e->getNbParticipant()+1);
                $emp->merge($e);
                $emp->flush($e);
                $participant->setIdE($e);
                if($e->getIdFr() != null){
                        $prop=$emp->getRepository(Freelancer::class)->find($e->getIdFr());
                }
                else{
                        $prop=$emp->getRepository(Societe::class)->find($e->getIdSo());
                }
                $email=(new NotificationEmail())
                        ->from('nadebessioud20@gmail.com')
                        ->to($prop->getEmail())
                        ->subject('Participation')
                        ->markdown($this->renderView('formation/FormationParticipationMAIL.html.twig',['user'=>$user,'e'=>$e,'typee'=>$typee]))
                        ->action("Liste participants?","http://127.0.0.1:8000/participantsEventPDF/$idev")
                        ->importance(NotificationEmail::IMPORTANCE_HIGH);
                    $mailer->send($email);

                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('nadebessioud20@gmail.com')
                    ->setTo($s->getEmail())
                    ->setSubject('Confirmation du participation')
                    ->setBody($this->renderView('formation/FormationMAIL.html.twig',['user'=>$user,'e'=>$e,'typee'=>$typee]), 'text/html');
                $mailer1->send($message);


            }
            else{
                $f=$emp->getRepository(Freelancer::class)->find($idf);
                $user=$emp->getRepository(Freelancer::class)->find($idf);
                $emp->merge($f);
                $emp->flush($f);
                $participant->setIdF($f);
                $e=$emp->getRepository(EventLoisir::class)->find($idev);
                $e->setNbParticipant($e->getNbParticipant()+1);
                $emp->merge($e);
                $emp->flush($e);
                $participant->setIdE($e);
                if($e->getIdFr() != null){
                        $prop=$emp->getRepository(Freelancer::class)->find($e->getIdFr());
                }
                else{
                        $prop=$emp->getRepository(Societe::class)->find($e->getIdSo());
                    }
                $email=(new NotificationEmail())
                        ->from('nadebessioud20@gmail.com')
                        ->to($prop->getEmail())
                        ->subject('Participation')
                        ->markdown($this->renderView('formation/FormationParticipationMAIL.html.twig',['user'=>$user,'e'=>$e,'typee'=>$typee]))
                        ->action("Liste participants?","http://127.0.0.1:8000/participantsEventPDF/$idev")
                        ->importance(NotificationEmail::IMPORTANCE_HIGH);
                    $mailer->send($email);

                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('nadebessioud20@gmail.com')
                    ->setTo($f->getEmail())
                    ->setSubject('Confirmation du participation')
                    ->setBody($this->renderView('formation/FormationMAIL.html.twig',['user'=>$user,'e'=>$e,'typee'=>$typee]), 'text/html');
            }

                $mailer1->send($message);
                $participant->setTypeE("$typee");
                $participant->setTypeU($typeu);
                $emp->persist($participant);
                $emp->flush();
                if($typeV=="call"){
                $call = $twilio->calls
                    ->create($num, // to
                        "+12562902100", // from
                        [
                            "twiml" => "<Response><Say>you have participate to the event</Say></Response>"
                        ]
                    );
                }
                if($typeC=="SMS"){
                $message=$twilio->messages->create($num,
                    array('from'=>'+12562902100','body'=>'hello hadha just test '));
                }
        }
        return $this->redirectToRoute("AfficherEvent",array('idu'=>1,'type'=>"freelancer"));
    }

}
