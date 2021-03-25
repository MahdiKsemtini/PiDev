<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\EventLoisir;
use App\Entity\Formation;
use App\Entity\Freelancer;
use App\Entity\Participant;
use App\Entity\Societe;
use App\Entity\Student;
use App\Form\FormationType;
use App\Form\ParticipantType;
use App\Form\SearchType;
use App\Form\StudentType;
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
    public function AfficherFormation(Request $request){
        $type=$request->get("type");
        $idu=$request->get("idu");
        $em=$this->getDoctrine()->getRepository(Formation::class);

        if($type=="freelancer"){
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

        $formations1=$em->findBy(array('Etat'=>1));;
        return $this->render("formation/AfficherFormation.html.twig",['formations'=>$formations,'forms'=>$formations1,'participation'=>$formparticipation]);
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
     */
    public function addFormation(\Symfony\Component\HttpFoundation\Request $request){


            $formation = new Formation();
            $em = $this->getDoctrine()->getManager();
            $emf = $this->getDoctrine()->getRepository(Freelancer::class);
            $form = $this->createForm(FormationType::class, $formation);
            $form->handleRequest($request);
            if ($form->isSubmitted() and $form->isValid()) {
                $freelancer = $emf->find(1);

                $em->merge($freelancer);
                $em->flush($freelancer);
                $formation->setIdFr($freelancer);
                $formation->setEtat(1);



                $em->persist($formation);
                $em->flush();
                $this->addFlash('msg', "Formation ajouté avec succées");
                return $this->redirectToRoute("Afficherformation", array('idu' => 1, 'type' => "freelancer"));
            }
            return $this->render("formation/AjouterFormation.html.twig", ['f' => $form->createView()]);


    }

    /**
     * @Route("/ModifierFormation/{id}",name="ModifierFormation")
     */
    public function updateStudent($id,\Symfony\Component\HttpFoundation\Request $request){
        $em=$this->getDoctrine()->getManager();
        $formation=$em->getRepository(Formation::class)->find($id);
        $form=$this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->flush();
            $this->addFlash('msg',"Formation modifié avec succées");
            return $this->redirectToRoute("Afficherformation",array('idu'=>0,'type'=>"freelancer"));
        }
        return $this->render("formation/AjouterFormation.html.twig",['f'=>$form->createView()]);


    }

    /**
     * @Route("/deleteFormation/{id}",name="deleteFormation")
     */
    public function deleteClass($id){
        $em=$this->getDoctrine()->getManager();
        $part=$em->getRepository(Participant::class)->findBy(array('idFO'=>$id));
        if($part!=null){
            $this->addFlash('info',"Cette formation ne peut pas étre supprimé car elle contient deja des participants;Veuillez contacter l'administrateur pour la supprimer");
        }
        else {
            $f = $em->getRepository(Formation::class)->find($id);
            $em->remove($f);
            $em->flush();
        }
        return $this->redirectToRoute("Afficherformation",array('idu'=>1,'type'=>"freelancer"));
    }

    /**
     * @Route("/deleteFormationP",name="deleteFormationP")
     */
    public function deleteFormationP(Request $request){
        $em=$this->getDoctrine()->getManager();
        $type=$request->get("type");
        $idu=$request->get("idu");
        $id=$request->get("id");
      if($type=="freelancer"){
          $p = $em->getRepository(Participant::class)->findOneBy(array('idF'=>$idu,'idFO'=>$id));
      }
      else{
          $p = $em->getRepository(Participant::class)->findOneBy(array('idS'=>$idu,'idFO'=>$id));
      }

            $em->remove($p);
            $em->flush();

        return $this->redirectToRoute("Afficherformation",array('idu'=>1,'type'=>"freelancer"));
    }

    /**
     * @Route("/triDateC",name="triDateC")
     */
    public function orderByDateC(FormationRepository $repository,Request $request){
        $type=$request->get("type");
        $idu=$request->get("idu");
        $em=$this->getDoctrine()->getRepository(Formation::class);
        $formsC=$repository->OrderByDateC();

        if($type=="freelancer"){
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

        return $this->render("formation/AfficherFormationTri.html.twig",['formations'=>$formations,'forms'=>$formsC,'participation'=>$formparticipation]);

    }

    /**
     * @Route("/triDateD",name="triDateD")
     */
    public function orderByDateD(FormationRepository $repository,Request $request){
        $type=$request->get("type");
        $idu=$request->get("idu");
        $formsD=$repository->OrderByDateD();
        $em=$this->getDoctrine()->getRepository(Formation::class);
        if($type=="freelancer"){
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


        return $this->render("formation/AfficherFormationTri.html.twig",['formations'=>$formations,'forms'=>$formsD,'participation'=>$formparticipation]);

    }

    /**
     * @Route("/addParticiper",options={"expose"=true}, name="addParticiper")
     */
    public function participer(\Symfony\Component\HttpFoundation\Request $request,MailerInterface $mailer,\Swift_Mailer $mailer1,\Twilio\Rest\Client $twilio){
        $typee="formation";
        $typeu="societe";
        $idfo=$request->get("idfo");
        $idev=0;
        $ids=1;
        $idf=0;
        $num=$request->get("num");
        $typeV=$request->get("type");
        $typeC=$request->get("typeC");
        $emp=$this->getDoctrine()->getManager();
        $participant=new Participant();

        if($typeu=="societe"){

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
           if($typeu=="societe"){
            $s=$emp->getRepository(Societe::class)->find($ids);
            $user=$emp->getRepository(Societe::class)->find($ids);
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
                    ->markdown($this->renderView('formation/FormationParticipationMAIL.html.twig',['user'=>$user,'e'=>$ev,'typee'=>$typee]))
                    ->action("Liste participants?","http://127.0.0.1:8000/participantsPDF/$idev")
                    ->importance(NotificationEmail::IMPORTANCE_HIGH);
                $mailer->send($email);
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('nadebessioud20@gmail.com')
                ->setTo($s->getEmail())
                ->setSubject('Confirmation du participation')
                ->setBody($this->renderView('formation/FormationMAIL.html.twig',['user'=>$user,'e'=>$ev,'typee'=>$typee]), 'text/html');
            $mailer1->send($message);


        }
        else{
            $f=$emp->getRepository(Freelancer::class)->find($idf);
            $user=$emp->getRepository(Freelancer::class)->find($idf);
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
                    ->markdown($this->renderView('formation/FormationParticipationMAIL.html.twig',['user'=>$user,'e'=>$ev,'typee'=>$typee]))
                    ->action("Liste participants?","http://127.0.0.1:8000/participantsPDF/$idfo")
                    ->importance(NotificationEmail::IMPORTANCE_HIGH);
                $mailer->send($email);
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('nadebessioud20@gmail.com')
                ->setTo($f->getEmail())
                ->setSubject('Confirmation du participation')
                ->setBody($this->renderView('formation/FormationMAIL.html.twig',['user'=>$user,'e'=>$ev,'typee'=>$typee]), 'text/html');
            $mailer1->send($message);


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
                array('from'=>'+12562902100','body'=>'hello hadha just test '));
        }
        }
       return $this->redirectToRoute("Afficherformation",array('idu'=>1,'type'=>"freelancer"));
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
     * @Route("/testAuto",name="x   ")
     */
    public function test(){
        return $this->render("formation/index.html.twig");
    }

}
