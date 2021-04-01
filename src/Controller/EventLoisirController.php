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
use App\Repository\AdminEventRepository;
use App\Repository\AdminRepository;
use App\Repository\EventLoisirRepository;
use App\Repository\FormationRepository;
use App\Repository\FreelancerRepository;
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
    public function AfficherFormation(Request $request,EventLoisirRepository $repository){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $idu=$session->get('id');
            $em=$this->getDoctrine()->getRepository(EventLoisir::class);
            if($session->get('prenom')!= null){
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
            if($session->get('prenom')!=null){
                $events1=$repository->getAllF($session->get('id'));
            }
            else{
                $events1=$repository->getAllS($session->get('id'));
            }



            return $this->render("event_loisir/AfficherEventLoisir.html.twig",['events'=>$events,'evenement'=>$events1,'participation'=>$formparticipation]);

        }
    }

    /**
     * @Route("/addEvent",name="addEvent")
     * @param Request $request
     * @param AdminRepository $adminRepository
     * @param AdminEventController $adminEventController
     * @param EventLoisirRepository $eventLoisirRepository
     * @param AdminEventRepository $adminEventRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addevent(Request $request, FreelancerRepository $freelancerRepository,AdminRepository $adminRepository, AdminEventController $adminEventController, EventLoisirRepository $eventLoisirRepository, AdminEventRepository $adminEventRepository){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $event=new EventLoisir();
            $em=$this->getDoctrine()->getManager();
            $emfr=$this->getDoctrine()->getRepository(Freelancer::class);
            $form=$this->createForm(EventLoisirType::class,$event);
            $form->handleRequest($request);
            if(($form->isSubmitted()) and ($form->isValid())){
                $event->setEtat(0);
                if($session->get('prenom') != null){
                    $freelancer=$freelancerRepository->find($session->get('id'));
                    $em->merge($freelancer);
                    $em->flush($freelancer);
                    $event->setIdFr($freelancer);
                    $freelanceremail = $freelancer->getEmail();
                    $em->persist($event);
                    $em->flush();
                    $adminEventController->EventLoisirToAdmin($adminRepository,$event->getId(),$freelanceremail,$eventLoisirRepository,$adminEventRepository);

                }
                else{
                    $societe=$em->getRepository(Societe::class)->find($session->get('id'));
                    $em->merge($societe);
                    $em->flush($societe);
                    $event->setIdSo($societe);
                    $em->persist($event);
                    $em->flush();
                    $societeemail = $societe->getEmail();
                    $adminEventController->EventLoisirToAdmin($adminRepository,$event->getId(),$societeemail,$eventLoisirRepository,$adminEventRepository);

                }


                $admins = $adminRepository->findBy(array('type'=>'Admin des events'));
                foreach ($admins as $admin) {
                    $admin->setNonapprouve($admin->getNonapprouve() + 1);
                }
                $em->flush();




                $this->addFlash('msg',"Evenement ajouté avec succées");
                return $this->redirectToRoute("AfficherEvent");
            }
            return $this->render("event_loisir/AjouterEvent.html.twig",['f'=>$form->createView()]);

        }

    }

    /**
     * @Route("/ModifierEvent/{id}",name="ModifierEvent")
     */
    public function updateEvent($id, Request $request){
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository(EventLoisir::class)->find($id);
        $form=$this->createForm(EventLoisirType::class,$event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->flush();
            return $this->redirectToRoute("AfficherEvent");
        }
        return $this->render("event_loisir/AjouterEvent.html.twig",['f'=>$form->createView()]);


    }
    /**
     * @Route("/deleteEvent/{id}",name="deleteEvent")
     */
    public function deleteEvent($id,\Swift_Mailer $mailer ){

        $em=$this->getDoctrine()->getManager();
        $e = $em->getRepository(EventLoisir::class)->find($id);
        $part=$em->getRepository(Participant::class)->findBy(array('idE'=>$id));
        if($part!=null) {
            for ($i = 0; $i < sizeof($part); $i++) {
                if ($part[$i]->getIdF() != null) {
                    $user = $em->getRepository(Freelancer::class)->find($part[$i]->getIdF());
                    $ev = $em->getRepository(Participant::class)->findOneBy(array('idE' => $id, 'idF' => $part[$i]->getIdF()));
                    $em->remove($ev);
                    $em->flush();
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('nadebessioud20@gmail.com')
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
                        ->setFrom('nadebessioud20@gmail.com')
                        ->setTo($userS->getEmail())
                        ->setSubject('annulation  evenement')
                        ->setBody($this->renderView('formation/FormationMAILDelete.html.twig', ['user' => $userS, 'e' => $e, 'type' => 'evenement']), 'text/html');
                    $mailer->send($message);
                }
            }
        }

        $this->addFlash('info', "evenement supprimé");

        $em->remove($e);
        $em->flush();

        return $this->redirectToRoute("AfficherEvent",array('idu'=>1,'type'=>"freelancer"));
    }


    /**
     * @Route("/triEventDateC",name="triEventDateC")
     */
    public function orderByDateC(EventLoisirRepository $repository,Request $request){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $idu=$session->get('id');

            $em=$this->getDoctrine()->getRepository(EventLoisir::class);

            if($session->get('prenom') != null){
                $eventC=$repository->OrderByDateCF($session->get('id'));
                $events=$em->findBy(array('idFr' => $idu,'Etat'=>1));
                $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idF'=>$idu,'typeE'=>'evenement'));
                $formparticipation=array();
                for($i=0;$i<sizeof($participation);$i++){
                    $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdE()->getId());
                }

            }
            else {
                $eventC=$repository->OrderByDateCS($session->get('id'));
                $events = $em->findBy(array('idSo' => $idu, 'Etat' => 1));
                $participation = $this->getDoctrine()->getRepository(Participant::class)->findBy(array('idS' => $idu, 'typeE' => 'evenement'));
                $formparticipation = array();
                for ($i = 0; $i < sizeof($participation); $i++) {
                    $formparticipation[sizeof($formparticipation)] = $em->find($participation[$i]->getIdE()->getId());
                }
            }
            return $this->render("event_loisir/AfficherEventLoisirTri.html.twig",['events'=>$events,'evenement'=>$eventC,'participation'=>$formparticipation]);
        }


    }
    /**
     * @Route("/triEventDateD",name="triEventDateD")
     */
    public function orderByDateD(EventLoisirRepository  $repository,Request $request){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $idu=$session->get("id");

            $em=$this->getDoctrine()->getRepository(EventLoisir::class);

            if($session->get('prenom') != null){
                $eventD=$repository->OrderByDateDF($session->get('id'));
                $events=$em->findBy(array('idFr' => $idu,'Etat'=>1));
                $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idF'=>$idu,'typeE'=>'evenement'));
                $formparticipation=array();
                for($i=0;$i<sizeof($participation);$i++){
                    $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdE()->getId());
                }

            }
            else {
                $eventD=$repository->OrderByDateDS($session->get('id'));
                $events = $em->findBy(array('idSo' => $idu, 'Etat' => 1));
                $participation = $this->getDoctrine()->getRepository(Participant::class)->findBy(array('idS' => $idu, 'typeE' => 'evenement'));
                $formparticipation = array();
                for ($i = 0; $i < sizeof($participation); $i++) {
                    $formparticipation[sizeof($formparticipation)] = $em->find($participation[$i]->getIdE()->getId());
                }
            }
            return $this->render("event_loisir/AfficherEventLoisirTri.html.twig",['events'=>$events,'evenement'=>$eventD,'participation'=>$formparticipation]);

        }
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
     * @Route("/deleteEventP",name="deleteEventP")
     */
    public function deleteEventP(Request $request){
        $session = $request->getSession();
        $em=$this->getDoctrine()->getManager();
        $idu=$session->get("id");
        $id=$request->get("id");
        if($session->get('prenom')!= null){
            $p = $em->getRepository(Participant::class)->findOneBy(array('idF'=>$idu,'idE'=>$id));
        }
        else{
            $p = $em->getRepository(Participant::class)->findOneBy(array('idS'=>$idu,'idE'=>$id));
        }

        $em->remove($p);
        $em->flush();

        return $this->redirectToRoute("AfficherEvent");
    }


    /**
     * @Route("/addParticiperE",options={"expose"=true}, name="addParticiperE")
     */
    public function participer(Request $request, MailerInterface $mailer, \Swift_Mailer $mailer1, \Twilio\Rest\Client $twilio){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $typee="evenement";
            if($session->get('prenom') != null){
                $typeu="freelancer";
            }
            else{
                $typeu="societe";
            }

            $idev=$request->get("idev");
            $ids=$session->get('id');
            $idf=$session->get('id');
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
                        ->setTo($session->get('email'))
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
                        ->setTo($session->get('email'))
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
                        array('from'=>'+12562902100','body'=>'Vous avez participer a un evenment'));
                }
            }
            return $this->redirectToRoute("AfficherEvent");

        }
    }

    /**
     * @Route("/CalenderF",name="CalenderF")
     */
    public function CalenderF(FormationRepository $repository){
        $type="freelancer";
        $em=$this->getDoctrine()->getManager();
        if($type=='freelancer'){
            $formation=$repository->findBy(array('idFr'=>1));
        }
        else{
            $formation=$repository->findBy(array('idSo'=>1));
        }

        $part=$em->getRepository(Participant::class)->findBy(array('idF'=>1,'typeU'=>$type));
        $participation=array();
        for($i=0;$i<sizeof($part);$i++){
            if($part[$i]->getTypeE()=="formation"){
                $participation[$i]=$repository->find($part[$i]->getIdFo());
            }
            else{
                $participation[$i]=$repository->find($part[$i]->getIdSo());
            }

        }
        $fms=[];
        foreach ($formation as $form){
            $fms[]=[
                'id'=>$form->getId(),
                'start'=>$form->getDateDebut()->format('Y-m-d H:i:s'),
                'end'=>$form->getDateFin()->format('Y-m-d H:i:s'),
                'title'=>$form->getLabelle(),
                'description'=>$form->getDescription(),
                'backgroundColor'=>'blue',
                'borderColor'=>'blue',
                'textColor'=>'white',
                'editable'=>true,


            ];
        }
        foreach ($participation as $p){
            $fms[]=[
                'id'=>$p->getId(),
                'start'=>$p->getDateDebut()->format('Y-m-d H:i:s'),
                'end'=>$p->getDateFin()->format('Y-m-d H:i:s'),
                'title'=>$p->getLabelle(),
                'description'=>$p->getDescription(),
                'backgroundColor'=>'red',
                'borderColor'=>'red',
                'textColor'=>'black',
                'editable'=>false,

            ];
        }

        $data=json_encode($fms);
        return $this->render("formation/Calender.html.twig",['data'=>$data]);
    }

    /**
     * @Route("/api/{id}/edit",name="api_event_edit", methods={"PUT"})
     */
    public function majFormation(?Formation $formation,Request $request,FormationRepository $repository){
        $donnees=json_decode($request->getContent());

        $code=200;
        $form=$repository->find($donnees->id);
        $form->setDateDebut(new \DateTime($donnees->start));
        if($donnees->allDay){
            $form->setDateFin(new \DateTime($donnees->start));
        }
        else{
            $form->setDateFin(new \DateTime($donnees->end));
        }
        $em=$this->getDoctrine()->getManager();
        $em->persist($form);
        $em->flush();

        return new Response('ok',$code);
    }


}