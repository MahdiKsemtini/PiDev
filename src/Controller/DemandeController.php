<?php

namespace App\Controller;

use App\Entity\DemandeEmploi;

use App\Entity\DemandeStage;
use App\Entity\Freelancer;
use App\Entity\OffreEmploi;
use App\Entity\OffreStage;
use App\Entity\Societe;
use App\Form\DemandeEmploiType;
use App\Form\DemandeStageType;
use App\Form\FreelancerProfileType;
use App\Repository\DemandeEmploiRepository;

use App\Repository\DemandeStageRepository;
use App\Repository\FreelancerRepository;

use App\Repository\QuizRepository;
use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DemandeController extends AbstractController
{


    /**
     * @param Request $request
     * @Route("/demande/{id_offre}", name="demande")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function Add_DemandeEmploi(Request $request, int $id_offre, FreelancerRepository $repository): Response
    {
        $freelancer = $repository->find($this->get('session')->get('id'));

        $em = $this->getDoctrine()->getManager();
        $e = $this->getDoctrine()->getManager();
        $offre = $e->getRepository(OffreEmploi::class)->find($id_offre);
        if ($offre instanceof OffreEmploi) {
            $DemandeEmploi = new DemandeEmploi();
            $DemandeEmploi->setOffreEmploi($offre);
            $DemandeEmploi->setDomaine($offre->getDomaine());
            $DemandeEmploi->setNomSociete($offre->getNomProjet());
            $form = $this->createForm(DemandeEmploiType::class, $DemandeEmploi);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {


                $em->persist($DemandeEmploi);
                $offre->addDemandeEmploi($DemandeEmploi);
                $DemandeEmploi->setFreelancer($freelancer);
                $freelancer->addDemandeEmploi($DemandeEmploi);

                $em->flush();
                return $this->redirectToRoute('AfficherDemande');

            }
        }
        return $this->render('demande/CreateDemandeE.html.twig', [
            'controller_name' => 'DemandeController',
            'form' => $form->createView(),
            'id_offre' => $id_offre,


        ]);

    }

    /**
     * @param Request $requests
     * @Route("/demandeS/{id_offre}", name="demandeS" )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function Add_DemandeStage(Request $requests, int $id_offre, FreelancerRepository $repository): Response
    {

        $freelancer = $repository->find($this->get('session')->get('id'));
        $em = $this->getDoctrine()->getManager();
        $e = $this->getDoctrine()->getManager();
        $offre = $e->getRepository(OffreStage::class)->find($id_offre);
        $DemandeStage = new DemandeStage();


        if ($offre instanceof OffreStage) {
            $DemandeStage->setOffreStage($offre);
            $DemandeStage->setDomaine($offre->getDomaine());
            $DemandeStage->setNomSociete($offre->getNomProjet());


            $form2 = $this->createForm(DemandeStageType::class, $DemandeStage);

            $form2->handleRequest($requests);

            if ($form2->isSubmitted() && $form2->isValid()) {


                $offre->addDemandeStage($DemandeStage);
                $DemandeStage->setFreelancer($freelancer);
                $freelancer->addDemandeStage($DemandeStage);

                $em->persist($DemandeStage);

                $em->flush();
                return $this->redirectToRoute('AfficherDemande');

            }

        }
        return $this->render('demande/CreateDemandeS.html.twig', [
            'controller_name' => 'DemandeController',
            'form2' => $form2->createView(),
            'id_offre' => $id_offre,


        ]);
    }


    /**
     ** @param DemandeEmploiRepository $repository
     * @return Symfony\Component\HttpFoundation\Response
     * @Route("/AfficherDemande", name="AfficherDemande")
     */
    public function AfficherDemandeE(DemandeEmploiRepository $repository, DemandeStageRepository $repo, Request $request, PaginatorInterface $paginator, FreelancerRepository $frepo): Response
    { $cat= new String_('domaine',"domaine2");
        $session = $request->getSession();
        if ($session->get('id') == null) {
            return $this->redirectToRoute('SignIn');
        } else {
            $freelancer = $frepo->find($this->get('session')->get('id'));
        }
        $DemandeEmplois = $freelancer->getDemandeEmplois();
        $DemandeStages = $freelancer->getDemandeStages();
        $pagination = $paginator->paginate($DemandeEmplois,
            // Define the page parameter
            $request->query->getInt('page', 1), 2);
        $pagination2 = $paginator->paginate($DemandeStages,
            // Define the page parameter
            $request->query->getInt('page1', 1), 2);

        return $this->render('demande/filter.html.twig',  [
            'freelancer' => $freelancer,

            'controller_name' => 'DemandeController',
            'pagination2' => $pagination2,

            'pagination' => $pagination,

        ]);
    }


    /**
     * @Route("/deleteDEmploi/{id}", name="deleteDEmploi")
     */
    public function deleteDEmploi($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Demande = $em->getRepository(DemandeEmploi::class)->find($id);
        $em->remove($Demande);
        $em->flush();
        return $this->redirectToRoute("AfficherDemande");
    }

    /**
     * @Route("/deleteDEmploiB/{id}", name="deleteDEmploib")
     */
    public function deleteDEmploiB($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Demande = $em->getRepository(DemandeEmploi::class)->find($id);
        $em->remove($Demande);
        $em->flush();
        return $this->redirectToRoute("back");
    }

    /**
     * @Route("/deleteDStage/{id}", name="deleteDStage")
     */
    public function deleteDStage($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Demande = $em->getRepository(DemandeStage::class)->find($id);
        $em->remove($Demande);
        $em->flush();
        return $this->redirectToRoute("AfficherDemande");
    }

    /**
     * @Route("/UpdateDemandeE/{id}",name="updateE")
     */
    function UpdateE(DemandeEmploiRepository $repository, $id, Request $request,FreelancerRepository  $repo)
    {
        $freelancer = $repo->find($this->get('session')->get('id'));
        $DemandeE = $repository->find($id);
        $form = $this->createForm(DemandeEmploiType::class, $DemandeE);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficherDemande');
        }
        return $this->render('demande/UpdateD.html.twig', [
            'form' => $form->createView(),
            'freelancer'=>$freelancer,
        ]);
    }

    /**
     * @Route("/UpdateDemandeS/{id}",name="updateS")
     */
    function UpdateS(DemandeStageRepository $repository, $id, Request $request,FreelancerRepository $repo)
    {
        $freelancer = $repo->find($this->get('session')->get('id'));
        $DemandeS = $repository->find($id);
        $form = $this->createForm(DemandeStageType::class, $DemandeS);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficherDemande');
        }
        return $this->render('demande/UpdateS.html.twig', [
            'form2' => $form->createView(),
            'freelancer'=>$freelancer,
        ]);
    }


    /**
     * @Route("/back", name="back", methods={"GET"})
     */
    public function back(DemandeEmploiRepository $Repository): Response
    {
        return $this->render('base_back.html.twig', [

        ]);
    }

    /**
     * @Route("/backD", name="backD", methods={"GET"})
     */
    public function backD(DemandeEmploiRepository $Repository, DemandeStageRepository $repo): Response
    {
        return $this->render('demande/AfficherBack.html.twig', [
            'demandes' => $Repository->findAll(),
            'demandesS' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/listD", name="listD", methods={"GET"})
     */
    public function listD(DemandeEmploiRepository $Repository): Response
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');


        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('demande/DemandeEPDF.html.twig', [
            'demandes' => $Repository->findAll(),
        ]);


        $dompdf->loadHtml($html);


        $dompdf->setPaper('A4', 'portrait');


        $dompdf->render();


        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }


    /**
     * @Route("/listS", name="listS", methods={"GET"})
     */
    public function listS(DemandeStageRepository $Repository): Response
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');


        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('demande/DemandeSPDF.html.twig', [
            'demandes' => $Repository->findAll(),
        ]);


        $dompdf->loadHtml($html);


        $dompdf->setPaper('A4', 'portrait');


        $dompdf->render();


        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/searchDemploi ", name="searchDemploi",methods={"GET"} )
     */
    public function searchDemandeE(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(DemandeEmploi::class);
        $requestString=$request->get('searchValue');
        $Demploi = $repository->searchDomaine($requestString);
        $jsonContent = $Normalizer->normalize($Demploi, 'json',['groups'=>'Demploi']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }

}


