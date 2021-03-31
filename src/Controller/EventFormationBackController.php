<?php

namespace App\Controller;

use App\Entity\EventLoisir;
use App\Entity\Formation;
use App\Entity\Freelancer;
use App\Entity\Participant;
use App\Entity\Societe;
use App\Form\EventLoisirType;
use App\Form\FormationType;
use App\Form\SearchFType;
use App\Form\SearchType;
use App\Repository\EventLoisirRepository;
use App\Repository\FormationRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EventFormationBackController extends AbstractController
{
    /*Affichage des formations et evenements*/
    /**
     * @Route("/Afficherallformationback", name="Afficherallformationback",methods={"GET","POST"})
     */
    public function AfficherAllFormationBack(FormationRepository  $formationRepository,\Symfony\Component\HttpFoundation\Request $request, PaginatorInterface $paginator){
        $eme=$this->getDoctrine()->getRepository(EventLoisir::class);
        $event=$eme->findBy(array('Etat'=>1));
        $formation = new Formation();
        $searchForm = $this->createForm(SearchFType::class,$formation);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $labelle = $searchForm['Labelle']->getData();
            $donnees = $formationRepository->search($labelle);
            return $this->redirectToRoute('search', array('labelle' => $labelle));
        }
        $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy([],['id' => 'desc']);

        // Paginate the results of the query
        $formations = $paginator->paginate(
        // Doctrine Query, not results
            $donnees,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        $events = $paginator->paginate(
        // Doctrine Query, not results
            $event,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );



        return $this->render('event_formation_back/AfficherFormationBack.html.twig', [
            'forms' => $formations,
            'events'=>$events,
            'searchForm' => $searchForm->createView()
        ]);

    }

    /*Modification d'une formation*/
    /**
     * @Route("/ModifierFormationBack/{id}",name="ModifierFormationBack")
     */
    public function updateFormationBack($id,\Symfony\Component\HttpFoundation\Request $request){
        $em=$this->getDoctrine()->getManager();
        $formation=$em->getRepository(Formation::class)->find($id);
        $form=$this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute("Afficherallformationback");
        }
        return $this->render("formation/AjouterFormation.html.twig",['f'=>$form->createView()]);
    }

    /*Suppression d'une formation*/
    /**
     * @Route("/deleteFormationBack/{id}",name="deleteFormationBack")
     */
    public function deleteFormationBack($id,\Swift_Mailer $mailer){
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


        return $this->redirectToRoute("Afficherallformationback");
    }

    /*creation du PDF des participants*/
    /**
     * @Route("/participantsPDF/{id}", name="participantsPDF", methods={"GET"})
     */
    public function listh($id): Response
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
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('event_formation_back/FormationPDF.html.twig', [
            'users' =>$users ,'societe'=>$societe ,'formation'=>$form ,'type'=>$participant1->getTypeE()]);

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


    /* Recherche d'une formation avec AJAX*/
    /**
     * @Route("/searchFormation", name="searchFormation")
     */
    public function searchFormation(Request $request,NormalizerInterface $Normalizer,FormationRepository $repository)
    {

        $requestString=$request->get('searchValue');
        $formations = $repository->search($requestString);
        $jsonContent = $Normalizer->normalize($formations, 'json',['groups'=>'formation:read']);
        $retour=json_encode($jsonContent);

        return new Response($retour);

    }

    /*Modifier un event*/
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

    /*supprimer event*/

    /**
     * @Route("/deleteEventBack/{id}",name="deleteEventBack")
     */
    public function deleteEventBack($id,\Swift_Mailer $mailer){
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

        return $this->redirectToRoute("Afficherallformationback",array('id'=>0));
    }

    /*Recherche d'un event*/
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