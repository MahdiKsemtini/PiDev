<?php

namespace App\Controller;

use App\Entity\DemandeEmploi;

use App\Entity\DemandeStage;
use App\Entity\Freelancer;
use App\Entity\OffreEmploi;
use App\Entity\Societe;
use App\Form\DemandeEmploiType;
use App\Form\DemandeStageType;
use App\Repository\DemandeEmploiRepository;

use App\Repository\DemandeStageRepository;
use App\Repository\FreelancerRepository;
use App\Repository\ProductRepository;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class DemandeController extends AbstractController
{


    /**
     * @param Request $request
     * @Route("/demande/{id_offre}", name="demande")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function Add_DemandeEmploi(Request $request,int $id_offre): Response
    {$DemandeEmploi= new DemandeEmploi();
    $DemandeStage= new DemandeStage();
        $e=$this->getDoctrine()->getManager();
        $offre_emploi=$e->getRepository(OffreEmploi::class)->find($id_offre);

        $DemandeEmploi->setOffreEmploi($offre_emploi);

        $form=$this->createForm(DemandeEmploiType::class,$DemandeEmploi);
        $form->handleRequest($request);

        $form2=$this->createForm(DemandeStageType::class,$DemandeStage);
        $form2->handleRequest($request);




    if($form->isSubmitted() && $form->isValid())
    {


        $em=$this->getDoctrine()->getManager();
        $em->persist($DemandeEmploi);
        $offre_emploi->addDemandeEmploi($DemandeEmploi);
        $em->flush();
        return $this->redirectToRoute('AfficherDemande');

    }else if($form2->isSubmitted() && $form2->isValid()){

        $em=$this->getDoctrine()->getManager();
        $em->persist($DemandeStage);

        $em->flush();
        return $this->redirectToRoute('AfficherDemande');

    }
        return $this->render('demande/CreateDemande.html.twig',[
            'controller_name' => 'DemandeController',
            'form'=>$form->createView(),
            'form2'=>$form2->createView(),
            'id_offre'=>$id_offre,

        ]);

}





    /**
     ** @param DemandeEmploiRepository $repository
     * @return Symfony\Component\HttpFoundation\Response
     * @Route("/AfficherDemande", name="AfficherDemande")
     */
    public function AfficherDemandeE(DemandeEmploiRepository $repository,DemandeStageRepository $repo,Request $request,PaginatorInterface $paginator): Response
    {
        $DemandeEmplois=$repository->findAll();
        $DemandeStages=$repo->findAll();
        $pagination = $paginator->paginate( $DemandeEmplois,
            // Define the page parameter
            $request->query->getInt('page', 1), 2);

        return $this->render('demande/AfficherDemande.html.twig', [

            'ds'=>$DemandeStages,
            'controller_name' => 'DemandeController',

            'pagination'=>$pagination,
        ]);
    }


/**
* @Route("/deleteDEmploi/{id}", name="deleteDEmploi")
*/
    public function deleteDEmploi($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Demande=$em->getRepository(DemandeEmploi::class)->find($id);
        $em->remove($Demande);
        $em->flush();
        return $this->redirectToRoute("AfficherDemande");
    }
    /**
     * @Route("/deleteDEmploiB/{id}", name="deleteDEmploib")
     */
    public function deleteDEmploiB($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Demande=$em->getRepository(DemandeEmploi::class)->find($id);
        $em->remove($Demande);
        $em->flush();
        return $this->redirectToRoute("back");
    }
    /**
     * @Route("/deleteDStage/{id}", name="deleteDStage")
     */
    public function deleteDStage($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Demande=$em->getRepository(DemandeStage::class)->find($id);
        $em->remove($Demande);
        $em->flush();
        return $this->redirectToRoute("AfficherDemande");
    }

    /**
     * @Route("/UpdateDemandeE/{id}",name="updateE")
     */
    function UpdateE(DemandeEmploiRepository $repository,$id,Request $request)
    {
        $DemandeE=$repository->find($id);
        $form=$this->createForm(DemandeEmploiType::class,$DemandeE);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficherDemande');
        }
        return $this->render('demande/UpdateD.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/UpdateDemandeS/{id}",name="updateS")
     */
    function UpdateS(DemandeStageRepository $repository,$id,Request $request)
    {
        $DemandeS=$repository->find($id);
        $form=$this->createForm(DemandeStageType::class,$DemandeS);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficherDemande');
        }
        return $this->render('demande/UpdateS.html.twig',[
            'form2'=>$form->createView()
        ]);
    }


    /**
     * @Route("/back", name="back", methods={"GET"})
     */
    public function index(DemandeEmploiRepository $Repository): Response
    {
        return $this->render('demande/AfficherBack.html.twig', [
            'demandes' => $Repository->findAll(),
        ]);
    }

    /**
     * @Route("/listD", name="listD", methods={"GET"})
     */
    public function listD(DemandeEmploiRepository $hotelRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('demande/AfficherBack.html.twig', [
            'demandes' => $hotelRepository->findAll(),
        ]);

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

}
