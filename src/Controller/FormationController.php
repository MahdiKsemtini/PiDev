<?php

namespace App\Controller;

use App\Entity\EventLoisir;
use App\Entity\Formation;
use App\Entity\Freelancer;
use App\Entity\Participant;
use App\Entity\Societe;
use App\Form\FormationType;
use App\Form\SearchType;
use App\Repository\AdminEventRepository;
use App\Repository\AdminRepository;
use App\Repository\EventLoisirRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\FormationRepository;
use App\Repository\FreelancerRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SocieteRepository;
use http\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="formation")
     */
    public function index(): Response
    {
        return $this->render('formation/AjouterFormation.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }


    /**
     * @Route("/Afficherformation", name="Afficherformation")
     */
    public function AfficherFormation(Request $request,FormationRepository $repository){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $idu=$session->get('id');
            $em=$this->getDoctrine()->getRepository(Formation::class);

            if($session->get('prenom')!=null){
                $formations=$em->findBy(array('idFr' => $idu,'Etat'=>1));
                $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idF'=>$idu,'typeE'=>'formation'));
                $formparticipation=array();
                for($i=0;$i<sizeof($participation);$i++){
                    $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdFO()->getId());
                }

            }
            else{
                $formations=$em->findBy(array('idSo' => $idu,'Etat'=>1));
                $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idS'=>$idu,'typeE'=>'formation'));
                $formparticipation=array();
                for($i=0;$i<sizeof($participation);$i++){
                    $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdFO()->getId());
                }
            }
            if($session->get('prenom')!=null){
                $formations1=$repository->getAllF($session->get('id'));
            }
            else{
                $formations1=$repository->getAllS($session->get('id'));
            }

            return $this->render("formation/AfficherFormation.html.twig",['formations'=>$formations,'forms'=>$formations1,'participation'=>$formparticipation]);

        }
    }
    /**
     * @Route("/Afficherallformation", name="Afficherallformation")
     */
    public function AfficherAllFormation(){
        $em=$this->getDoctrine()->getRepository(Formation::class);
        $formations1=$em->findBy(array('Etat'=>1));;
        return $this->render("formation/AfficherFormation.html.twig",['forms'=>$formations1]);
    }

    /**
     * @Route("/addFormation",name="addFormation")
     * @param Request $request
     * @param AdminRepository $adminRepository
     * @param AdminEventController $adminEventController
     * @param FormationRepository $formationRepository
     * @param AdminEventRepository $adminEventRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addevent(\Symfony\Component\HttpFoundation\Request $request,AdminRepository $adminRepository,AdminEventController $adminEventController,FormationRepository $formationRepository,AdminEventRepository $adminEventRepository){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $formation = new Formation();
            $em = $this->getDoctrine()->getManager();
            $emf = $this->getDoctrine()->getRepository(Freelancer::class);
            $form = $this->createForm(FormationType::class, $formation);
            $form->handleRequest($request);

            if ($form->isSubmitted() and $form->isValid()) {
                $formation->setEtat(0);

                if($session->get('prenom') != null){
                    $freelancer=$emf->find($session->get('id'));
                    $em->merge($freelancer);
                    $em->flush($freelancer);
                    $formation->setIdFr($freelancer);
                    $em->persist($formation);
                    $em->flush();
                    $freelanceremail = $freelancer->getEmail();
                    $adminEventController->FormationToAdmin($adminRepository,$formation->getId(),$freelanceremail,$formationRepository,$adminEventRepository);

                }
                else{
                    $societe=$em->getRepository(Societe::class)->find($session->get('id'));
                    $em->merge($societe);
                    $em->flush($societe);
                    $formation->setIdSo($societe);
                    $em->persist($formation);
                    $em->flush();
                    $societeemail = $societe->getEmail();
                    $adminEventController->FormationToAdmin($adminRepository,$formation->getId(),$societeemail,$formationRepository,$adminEventRepository);

                }


                $admins = $adminRepository->findBy(array('type'=>'Admin des events'));
                foreach ($admins as $admin) {
                    $admin->setNonapprouve($admin->getNonapprouve() + 1);

                }


                $em->flush();


                ;

                $this->addFlash('msg', "Formation ajouté avec succées");
                return $this->redirectToRoute("Afficherformation");
            }
            return $this->render("formation/AjouterFormation.html.twig", ['f' => $form->createView()]);

        }


    }

    /**
     * @Route("/ModifierFormation/{id}",name="ModifierFormation")
     */
    public function updateStudent($id,\Symfony\Component\HttpFoundation\Request $request){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $em=$this->getDoctrine()->getManager();
            $formation=$em->getRepository(Formation::class)->find($id);
            $form=$this->createForm(FormationType::class,$formation);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){

                $em->flush();
                $this->addFlash('msg',"Formation modifié avec succées");
                return $this->redirectToRoute("Afficherformation");
            }
            return $this->render("formation/AjouterFormation.html.twig",['f'=>$form->createView()]);

        }


    }

    /**
     * @Route("/deleteFormation/{id}",name="deleteFormation")
     */
    public function deleteClass($id,\Swift_Mailer $mailer){
        $em=$this->getDoctrine()->getManager();
        $part=$em->getRepository(Participant::class)->findBy(array('idFO'=>$id));
        $f = $em->getRepository(Formation::class)->find($id);
        if($part!=null){
            for($i=0;$i<sizeof($part);$i++){
                if($part[$i]->getIdF() != null){
                    $user=$em->getRepository(Freelancer::class)->find($part[$i]->getIdF());
                    $e=$em->getRepository(Participant::class)->findOneBy(array('idFO'=>$id,'idF'=>$part[$i]->getIdF()));
                    $em->remove($e);
                    $em->flush();
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('nadebessioud20@gmail.com')
                        ->setTo($user->getEmail())
                        ->setSubject('annulation du formation')
                        ->setBody($this->renderView('formation/FormationMAILDelete.html.twig',['user'=>$user,'e'=>$f,'type'=>'formation']), 'text/html');
                    $mailer->send($message);
                }
                else{
                    $userS=$em->getRepository(Societe::class)->find($part[$i]->getIdS());
                    $e1=$em->getRepository(Participant::class)->findOneBy(array('idFO'=>$id,'idS'=>$part[$i]->getIdS()));
                    $em->remove($e1);
                    $em->flush();
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('nadebessioud20@gmail.com')
                        ->setTo($userS->getEmail())
                        ->setSubject('annulation du formation')
                        ->setBody($this->renderView('formation/FormationMAILDelete.html.twig',['user'=>$userS,'e'=>$f,'type'=>'formation']), 'text/html');
                    $mailer->send($message);
                }

            }

        }
        $em->remove($f);
        $em->flush();

        $this->addFlash('info',"formation supprimée");
        return $this->redirectToRoute("Afficherformation",array('idu'=>1,'type'=>"freelancer"));
    }


    /**
     * @Route("/deleteFormationP",name="deleteFormationP")
     */
    public function deleteFormationP(Request $request){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $em=$this->getDoctrine()->getManager();
            $idu=$session->get("id");
            $id=$request->get("id");
            if($session->get('prenom')!= null){
                $p = $em->getRepository(Participant::class)->findOneBy(array('idF'=>$idu,'idFO'=>$id));
            }
            else{
                $p = $em->getRepository(Participant::class)->findOneBy(array('idS'=>$idu,'idFO'=>$id));
            }

            $em->remove($p);
            $em->flush();

            return $this->redirectToRoute("Afficherformation");
        }
    }

    /**
     * @Route("/triDateC",name="triDateC")
     */
    public function orderByDateC(FormationRepository $repository,Request $request){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $idu=$session->get("id");
            $em=$this->getDoctrine()->getRepository(Formation::class);


            if($session->get('prenom')!= null){
                $formsC=$repository->OrderByDateCF($session->get('id'));
                $formations=$em->findBy(array('idFr' => $idu,'Etat'=>1));
                $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idF'=>$idu,'typeE'=>'formation'));
                $formparticipation=array();
                for($i=0;$i<sizeof($participation);$i++){
                    $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdFO()->getId());
                }

            }
            else{
                $formsC=$repository->OrderByDateCS($session->get('id'));
                $formations=$em->findBy(array('idSo' => $idu,'Etat'=>1));
                $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idS'=>$idu,'typeE'=>'formation'));
                $formparticipation=array();
                for($i=0;$i<sizeof($participation);$i++){
                    $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdFO()->getId());
                }
            }

            return $this->render("formation/AfficherFormationTri.html.twig",['formations'=>$formations,'forms'=>$formsC,'participation'=>$formparticipation]);

        }

    }

    /**
     * @Route("/triDateD",name="triDateD")
     */
    public function orderByDateD(FormationRepository $repository,Request $request){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $idu=$session->get("id");

            $em=$this->getDoctrine()->getRepository(Formation::class);
            if($session->get('prenom')!= null){
                $formsD=$repository->OrderByDateDF($session->get('id'));
                $formations=$em->findBy(array('idFr' => $idu,'Etat'=>1));
                $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idF'=>$idu,'typeE'=>'formation'));
                $formparticipation=array();
                for($i=0;$i<sizeof($participation);$i++){
                    $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdFO()->getId());
                }

            }
            else{
                $formsD=$repository->OrderByDateD($session->get('id'));
                $formations=$em->findBy(array('idSo' => $idu,'Etat'=>1));
                $participation=$this->getDoctrine()->getRepository(Participant::class)->findBy(array('idS'=>$idu,'typeE'=>'formation'));
                $formparticipation=array();
                for($i=0;$i<sizeof($participation);$i++){
                    $formparticipation[sizeof($formparticipation)]=$em->find($participation[$i]->getIdFO()->getId());
                }
            }


            return $this->render("formation/AfficherFormationTri.html.twig",['formations'=>$formations,'forms'=>$formsD,'participation'=>$formparticipation]);

        }

    }

    /**
     * @Route("/addParticiper",options={"expose"=true}, name="addParticiper")
     */
    public function participer(\Symfony\Component\HttpFoundation\Request $request,MailerInterface $mailer,\Swift_Mailer $mailer1,\Twilio\Rest\Client $twilio){
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $typee="formation";
            $idfo=$request->get("idfo");
            $idev=0;
            $ids=$session->get('id');
            $idf=$session->get('id');
            $num=$request->get("num");
            $typeV=$request->get("type");
            $typeC=$request->get("typeC");
            $emp=$this->getDoctrine()->getManager();
            $participant=new Participant();

            if($session->get('prenom')== null){

                $part=$emp->getRepository(Participant::class)->findBy(array('idS' => $ids,'idFO'=>$idfo));
                if($part!=null){
                    $this->addFlash('alert',"Vous avez déja participer à cette formation");
                }
            }
            else{
                $part=$emp->getRepository(Participant::class)->findBy(array('idF' => $idf,'idFO'=>$idfo));
                if($part!=null){
                    $this->addFlash('alert',"Vous avez déja participer à cette formation");
                }
            }

            if($part==null){
                if($session->get('prenom')== null){
                    $s=$emp->getRepository(Societe::class)->find($ids);
                    $emp->merge($s);
                    $emp->flush($s);
                    $participant->setIdS($s);
                    $fo=$emp->getRepository(Formation::class)->find($idfo);
                    $ev=$emp->getRepository(Formation::class)->find($idfo);
                    $emp->merge($fo);
                    $emp->flush($fo);
                    $participant->setIdFO($fo);
                    if($fo->getIdFr() != null){
                        $prop=$emp->getRepository(Freelancer::class)->find($fo->getIdFr());
                    }
                    else{
                        $prop=$emp->getRepository(Societe::class)->find($fo->getIdSo());
                    }
                    $email=(new NotificationEmail())
                        ->from('nadebessioud20@gmail.com')
                        ->to($prop->getEmail())
                        ->subject('Participation')
                        ->markdown($this->renderView('formation/FormationParticipationMAIL.html.twig',['user'=>$s,'e'=>$ev,'typee'=>$typee]))
                        ->action("Liste participants?","http://127.0.0.1:8000/participantsPDF/$idev")
                        ->importance(NotificationEmail::IMPORTANCE_HIGH);
                    $mailer->send($email);
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('nadebessioud20@gmail.com')
                        ->setTo($session->get('email'))
                        ->setSubject('Confirmation du participation')
                        ->setBody($this->renderView('formation/FormationMAIL.html.twig',['user'=>$s,'e'=>$ev,'typee'=>$typee]), 'text/html');
                    $mailer1->send($message);


                }
                else{
                    $f=$emp->getRepository(Freelancer::class)->find($idf);
                    $emp->merge($f);
                    $emp->flush($f);
                    $participant->setIdF($f);
                    $fo=$emp->getRepository(Formation::class)->find($idfo);
                    $ev=$emp->getRepository(Formation::class)->find($idfo);
                    $emp->merge($fo);
                    $emp->flush($fo);
                    $participant->setIdFO($fo);
                    if($fo->getIdFr() != null){
                        $prop=$emp->getRepository(Freelancer::class)->find($fo->getIdFr());
                    }
                    else{
                        $prop=$emp->getRepository(Societe::class)->find($fo->getIdSo());
                    }
                    $email=(new NotificationEmail())
                        ->from('nadebessioud20@gmail.com')
                        ->to($prop->getEmail())
                        ->subject('Participation')
                        ->markdown($this->renderView('formation/FormationParticipationMAIL.html.twig',['user'=>$f,'e'=>$ev,'typee'=>$typee]))
                        ->action("Liste participants?","http://127.0.0.1:8000/participantsPDF/$idfo")
                        ->importance(NotificationEmail::IMPORTANCE_HIGH);
                    $mailer->send($email);
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('nadebessioud20@gmail.com')
                        ->setTo($session->get('email'))
                        ->setSubject('Confirmation du participation')
                        ->setBody($this->renderView('formation/FormationMAIL.html.twig',['user'=>$f,'e'=>$ev,'typee'=>$typee]), 'text/html');
                    $mailer1->send($message);


                }
                if($session->get('prenom') != null){
                    $typeu="freelancer";
                }
                else{
                    $typeu="societe";
                }
                $participant->setTypeE("$typee");
                $participant->setTypeU($typeu);
                $emp->persist($participant);
                $emp->flush();

                if($typeV=="call"){
                    $call = $twilio->calls
                        ->create($num, // to
                            "+12562902100", // from
                            [
                                "twiml" => "<Response><Say>you have participate to an formation</Say></Response>"
                            ]
                        );
                }
                if($typeC=="SMS"){
                    $message=$twilio->messages->create($num,
                        array('from'=>'+12562902100','body'=>'Vous avez participer a un formation'));
                }
            }
            return $this->redirectToRoute("Afficherformation");

        }
    }








    /**
     * @Route("/participantsEventPDF/{id}", name="participantsEventPDF", methods={"GET"})
     */
    public function EventPDF($id): Response
    {
        $pr=$this->getDoctrine()->getRepository(Participant::class);
        $fr=$this->getDoctrine()->getRepository(Freelancer::class);
        $sr=$this->getDoctrine()->getRepository(Societe::class);
        $ev=$this->getDoctrine()->getRepository(EventLoisir::class);
        $event=$ev->find($id);
        $participant=$pr->findBy(array('idE'=>$id));
        $participant1=$pr->findOneBy(array('idE'=>$id));
        $users=array();
        $societe=array();

        for($i=0;$i<sizeof($participant);$i++){

            if($participant[$i]->getTypeU()=="societe"){
                $societe[sizeof($societe)]=$sr->find($participant[$i]->getIdS()->getId());

            }
            else{
                $users[sizeof($users)]=$fr->find($participant[$i]->getIdF()->getId());
            }
        }
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('formation/FormationPDF.html.twig', [
            'users' =>$users ,'societe'=>$societe ,'formation'=>$event ,'type'=>$participant1->getTypeE()]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }




    /**
     * @Route("/search/{labelle}", name="search", methods={"GET","POST"}))
     */


    public function search(FormationRepository $formationRepository,$labelle,\Symfony\Component\HttpFoundation\Request $request,PaginatorInterface $paginator): Response
    {
        $eme=$this->getDoctrine()->getRepository(EventLoisir::class);
        $event=$eme->findBy(array('Etat'=>1));
        $formation = new Formation();
        $searchForm = $this->createForm(SearchType::class,$formation);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $labelle = $searchForm['Labelle']->getData();
            $donnees = $formationRepository->search($labelle);
            return $this->redirectToRoute('search', array('labelle' => $labelle));
        }
        $formation = $formationRepository->search($labelle);
        // Paginate the results of the query
        $formations = $paginator->paginate(
        // Doctrine Query, not results
            $formation,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        return $this->render('formation/AfficherFormationBack.html.twig', [
            'forms' => $formations,
            'events'=>$event,
            'searchForm' => $searchForm->createView(),

        ]);
    }

    /**
     * @Route("/mapF/{id}", name="mapF"))
     */
    public function map($id){
        $em=$this->getDoctrine()->getRepository(Formation::class);
        $formaion=$em->find($id);
        return $this->render('formation/FormationMAP.html.twig', [
            'f'=>$formaion

        ]);

    }


    /**
     * @Route("/participantsF/{id}", name="participantsF")
     */
    public function participantF($id): Response
    {
        $pr=$this->getDoctrine()->getRepository(Participant::class);
        $fr=$this->getDoctrine()->getRepository(Freelancer::class);
        $sr=$this->getDoctrine()->getRepository(Societe::class);
        $for=$this->getDoctrine()->getRepository(Formation::class);
        $form=$for->find($id);
        $participant=$pr->findBy(array('idFO'=>$id));
        $participant1=$pr->findOneBy(array('idFO'=>$id));
        $users=array();
        $societe=array();

        for($i=0;$i<sizeof($participant);$i++){

            if($participant[$i]->getTypeU()=="societe"){
                $societe[sizeof($societe)]=$sr->find($participant[$i]->getIdS()->getId());

            }
            else{
                $users[sizeof($users)]=$fr->find($participant[$i]->getIdF()->getId());
            }
        }

        return $this->render('formation/FormationPDF.html.twig', [
            'users' =>$users ,'societe'=>$societe,'formation'=>$form ,'type'=>$participant1->getTypeE()]);

    }

    /**
     * @Route("/testSMS",name="testSMS")
     */
    public function sms(\Twilio\Rest\Client $twilio){
        $message=$twilio->messages->create('+21629658549',
            array('from'=>'+12562902100','body'=>'hello hadha just test '));


        return $this->redirectToRoute("Afficherformation",array('idu'=>1,'type'=>"freelancer"));
    }



    /**
     * @Route("/GetLatLng",name="GetLatLng")
     */
    public function GetLatLng(Request $request){
        $lat=$request->get("lat");
        $lng=$request->get('lng');
        $formation=new Formation();
        $formation->setLat($lat);
        $formation->setLng($lng);
        return $formation;
    }

    /**
     * @Route("/testAuto",name="testAuto")
     */
    public function test(\Twilio\Rest\Client $twilio){
        $call = $twilio->calls
            ->create("+21629658549", // to
                "+12562902100", // from
                [
                    "twiml" => "<Response><Say>you have participate to an formation</Say></Response>"
                ]
            );
        return $this->render("formation/index.html.twig");
    }

    /**
     * @Route("/CalenderF",name="CalenderF")
     */
    public function CalenderF(FormationRepository $repository,Request $request){
        $session=$request->getSession();
        $id=$session->get('id');

        if($session->get('prenom')!=null){
            $type="freelancer";
        }
        else{
            $type="societe";
        }

        $em=$this->getDoctrine()->getManager();

        if($type=='freelancer'){
            $formation=$repository->findBy(array('idFr'=>$id,'Etat'=>1));
            $events=$em->getRepository(EventLoisir::class)->findBy(array('idFr'=>$id,'Etat'=>1));
            $part=$em->getRepository(Participant::class)->findBy(array('idF'=>$id,'typeU'=>$type));
                    }
        else{
            $formation=$repository->findBy(array('idSo'=>$id,'Etat'=>1));
            $events=$em->getRepository(EventLoisir::class)->findBy(array('idSo'=>$id,'Etat'=>1));
            $part=$em->getRepository(Participant::class)->findBy(array('idS'=>$id,'typeU'=>$type));
        }


        $participation=array();
        for($i=0;$i<sizeof($part);$i++){
            if($part[$i]->getTypeE()=="formation"){
                $participation[$i]=$repository->find($part[$i]->getIdFo());
            }
            else{
                $participation[$i]=$em->getRepository(EventLoisir::class)->find($part[$i]->getIdE());
            }

        }

        $fms=[];
        foreach ($events as $event){
            $fms[]=[
                'id'=>$event->getId(),
                'start'=>$event->getDateDebut()->format('Y-m-d H:i:s'),
                'end'=>$event->getDateFin()->format('Y-m-d H:i:s'),
                'title'=>$event->getLabelle(),
                'description'=>$event->getDescription(),
                'backgroundColor'=>'green',
                'borderColor'=>'green',
                'textColor'=>'white',
                'editable'=>true,


            ];
        }
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
    public function majFormation(?Formation $formation,Request $request,FormationRepository $repository,EventLoisirRepository $rep){
        $donnees=json_decode($request->getContent());

        $code=200;
        if($donnees->backgroundColor=="yellow"){
            $event=$rep->find($donnees->id);
            $event->setDateDebut(new \DateTime($donnees->start));
            if($donnees->allDay){
                $event->setDateFin(new \DateTime($donnees->start));
            }
            else{
                $event->setDateFin(new \DateTime($donnees->end));
            }
            $em=$this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
        }
        else if($donnees->backgroundColor=="blue"){
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
        }

        return new Response('ok',$code);
    }


}