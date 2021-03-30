<?php

namespace App\Controller;


use App\Entity\EventLoisir;
use App\Entity\Formation;
use App\Entity\Freelancer;
use App\Entity\Participant;


use App\Form\FormationType;
use App\Form\SearchType;
use App\Repository\AdminEventRepository;
use App\Repository\AdminRepository;
use App\Repository\EventLoisirRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/addFormation",name="addFormation")
     */
    public function addevent(\Symfony\Component\HttpFoundation\Request $request,AdminRepository $adminRepository,AdminEventController $adminEventController,FormationRepository $formationRepository,AdminEventRepository $adminEventRepository){


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



            $admins = $adminRepository->findBy(array('type'=>'Admin des evenements'));
            foreach ($admins as $admin) {
                $admin->setNonapprouve($admin->getNonapprouve() + 1);

            }

            $em->persist($formation);
            $em->flush();

            $freelanceremail = $freelancer->getEmail();
            $adminEventController->EventLoisirToAdmin($adminRepository,$formation->getId(),$freelanceremail->getEmail(),$formationRepository,$adminEventRepository);

            $this->addFlash('msg', "Formation ajouté avec succées");
            return $this->redirectToRoute("Afficherformation", array('idu' => 1, 'type' => "freelancer"));
        }
        return $this->render("formation/AjouterFormation.html.twig", ['f' => $form->createView()]);


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


}