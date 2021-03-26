<?php

namespace App\Controller;

use App\Entity\OffreStage;
use App\Form\StageType;
use App\Form\StageUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\OffreStageRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OffreStageController extends AbstractController
{
    /**
     * @Route("/stage", name="offre_stage")
     */
    public function index(): Response
    {
        return $this->render('offre_stage/CreateOffreStage.html.twig', [
            'controller_name' => 'OffreStageController',
        ]);
    }

     /**
     * @Route("/showStage", name="showStage")
     */
    public function ShowOffreStage(PaginatorInterface $paginator,Request $request): Response
    {
        
            $em=$this->getDoctrine()->getRepository(OffreStage::class);
            $list=$em->findAll();
            $count=$em->countNbOffre();
            $list = $paginator->paginate(
                // Doctrine Query, not results
                $list,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                5
            );
            return $this->render('offre_stage/showOffreStage.html.twig',["l"=>$list,"c"=>$count]);
        
    }

    /**
     * @Route("/addStage", name="addStage")
     * @param Request $request
     */
    public function addStage(Request $request){
        $stage = new OffreStage();
        $form = $this->createForm(StageType::class, $stage);
       // $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
          //  dd();
            $em = $this->getDoctrine()->getManager();
            $newDate= new \DateTime('now');
            $stage->setDateCreation($newDate);
            $em->persist($stage);
            $em->flush();
            return $this->redirectToRoute('showStage');
        }
        return $this->render('offre_stage/CreateOffreStage.html.twig', [
            "f" => $form->createView(),
        ]);
    }

    /**
     * @Route("/deleteStage/{id}", name="deleteStage")
     */
    public function deleteStage($id)
    {
        $em=$this->getDoctrine()->getManager();
        $offre=$em->getRepository(OffreStage::class)->find($id);
        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute("showStage");
    }

    /**
     * @Route("/deleteOwnStage/{id}", name="deleteOwnStage")
     */
    public function deleteOwnStage($id)
    {
        $em=$this->getDoctrine()->getManager();
        $offre=$em->getRepository(OffreStage::class)->find($id);
        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute("showuownS");
    }

     /**
     * @Route("/editStage/{id}", name="editStage")
     */
    public function editStage(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $stage = $em->getRepository(OffreStage::class)->find($id);
        $form = $this->createForm(StageUpdateType::class, $stage);
      //  $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
         //   $em->persist($emploi);
            $em->flush();
            return $this->redirectToRoute('showStage');
        }
        return $this->render('offre_stage/updateOffreStage.html.twig', [
            "f" => $form->createView(),
        ]);
    }



     /**
     * @Route("/editOwnStage/{id}", name="editOwnStage")
     */
    public function editOwnStage(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $stage = $em->getRepository(OffreStage::class)->find($id);
        $form = $this->createForm(StageUpdateType::class, $stage);
      //  $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
         //   $em->persist($emploi);
            $em->flush();
            return $this->redirectToRoute('showuownS');
        }
        return $this->render('offre_stage/updateOffreStage.html.twig', [
            "f" => $form->createView(),
        ]);
    }


     /**
     * @Route("/showAllStage", name="showAllStage")
     */
    public function ShowAllStage(Request $request,PaginatorInterface $paginator): Response
    {
        
            $em=$this->getDoctrine()->getRepository(OffreStage::class);
            $list=$em->findAll();

            $list = $paginator->paginate(
                // Doctrine Query, not results
                $list,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                5
            );
           

            return $this->render('offre_stage/showAllStage.html.twig',["l"=>$list]);
        
    }


      /**
     * @Route("/showOwnStage/{id}", name="showuownS")
     */
    public function ShowOwnOffreStage($id): Response
    {
        
            $em=$this->getDoctrine()->getRepository(OffreStage::class);
            $list=$em->findBy(array('societe'=>$id));
            return $this->render('offre_stage/showOwnStage.html.twig',["l"=>$list]);
        
    }


    
    /**
     * @Route("/listhS", name="listhS", methods={"GET"})
     */
    public function listh(OffreStageRepository $OffreStageRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('offre_stage/PrintPdfStage.html.twig', [
            'l' => $OffreStageRepository->findAll(),
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


    /**
     * @Route("/searchStage ", name="searchStage")
     */
    public function searchStage(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(OffreStage::class);
        $requestString=$request->get('searchValue');
        $stage = $repository->findStageParNom($requestString);
        $jsonContent = $Normalizer->normalize($stage, 'json',['groups'=>'stage']);
        $retour=json_encode($jsonContent);
        return new Response($retour);
      
    }

      /**
     * @Route("/filtreStage/{domaine} ", name="filtreStage")
     * @param $domaine
     */
    public function filtreStage(NormalizerInterface $Normalizer, $domaine)
    {
        $repository = $this->getDoctrine()->getRepository(OffreStage::class);
       
        $stage = $repository->findDomaine($domaine);
        $jsonContent = $Normalizer->normalize($stage, 'json',['groups'=>'stage']);
        $retour=json_encode($jsonContent);
        return new Response($retour);
      
    }

    /**
     * @Route("/dateExpiration ", name="dateExpiration")
     */
    public function dateExpiration(OffreStageRepository $stageRepository): Response
    {
        $stageRepository->updateDate();
        return $this->redirectToRoute('showStage');
    }

}
