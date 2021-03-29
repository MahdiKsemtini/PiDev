<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Form\EmploiType;
use App\Form\EmploiUpdateType;
use App\Repository\OffreEmploiRepository;
use App\Repository\OffreStageRepository;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;



class OffreEmploiController extends AbstractController
{
    //public function __construct($dir)
    //{
       // $this->upload_dir = $dir;
    //}

    /**
     * @Route("/showEmploi", name="showEmploi")
     */
    public function ShowOffreEmploi(PaginatorInterface $paginator, Request $request): Response
    {

        $em=$this->getDoctrine()->getRepository(OffreEmploi::class);
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
        // echo "<script>alert('".$list."')</script>";
        //  return $this->redirectToRoute('showEmploi',["c"=>$count]);
        return $this->render('offre_emploi/showOffreEmploi.html.twig',["l"=>$list,"c"=>$count]);

    }

    /**
     * @Route("/addEmploi", name="addEmploi")
     * @param Request $request
     */
    public function addEmploi(Request $request){

        $emploi = new OffreEmploi();
        //   $emploi->setFichier(($baseurl . "/uploads/cantine/" . $newFilename));
        $form = $this->createForm(EmploiType::class, $emploi);
        // $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            //  dd();
            $em = $this->getDoctrine()->getManager();
            $em->persist($emploi);
            $em->flush();
            return $this->redirectToRoute('showEmploi');
        }
        return $this->render('offre_emploi/CreateOffreEmploi.html.twig', [
            "f" => $form->createView(),
        ]);



    }



    /**
     * @Route("/deleteEmploi/{id}", name="deleteEmploi")
     */
    public function deleteEmploi($id)
    {
        $em=$this->getDoctrine()->getManager();
        $offre=$em->getRepository(OffreEmploi::class)->find($id);
        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute("showEmploi");
    }
    /**
     * @Route("/editEmploi/{id}", name="editEmploi")
     */
    public function editEmploi(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $emploi = $em->getRepository(OffreEmploi::class)->find($id);
        $form = $this->createForm(EmploiUpdateType::class, $emploi);
        //  $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            //   $em->persist($emploi);
            $em->flush();
            return $this->redirectToRoute('showEmploi');
        }
        return $this->render('offre_emploi/updateOffreEmploi.html.twig', [
            "f" => $form->createView(),
        ]);


    }

    /**
     * @Route("/showAllEmploi", name="showutlis")
     */
    public function ShowOffreUtilis(Request $request,PaginatorInterface $paginator): Response
    {

        $em=$this->getDoctrine()->getRepository(OffreEmploi::class);
        $list=$em->findAll();

        $list = $paginator->paginate(
        // Doctrine Query, not results
            $list,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );


        return $this->render('offre_emploi/showAllemploi.html.twig',["l"=>$list]);

    }

    /**
     * @Route("/showOwnEmploi", name="showuown")
     */
    public function ShowOwnOffre(): Response
    {

        $em=$this->getDoctrine()->getRepository(OffreEmploi::class);
        $list=$em->findAll();
        return $this->render('offre_emploi/showOwnemploi.html.twig',["l"=>$list]);

    }


    /**
     * @Route("/NbEmploi", name="nbEmploi")
     */
    public function ShowCount( OffreEmploiRepository $offreEmploiRepository): Response
    {

        $em=$this->getDoctrine()->getRepository(OffreEmploi::class);
        $list=$em->countNbOffre();
        // echo "<script>alert('".$list."')</script>";
        return $this->redirectToRoute('showEmploi',["c"=>$list]);

    }


    /**
     * @Route("/listh", name="listh", methods={"GET"})
     */
    public function listh(OffreEmploiRepository $offreEmploiRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('offre_emploi/PrintPDF.html.twig', [
            'l' => $offreEmploiRepository->findAll(),
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
     * @Route("/searchEmploi ", name="searchEmploi")
     */
    public function searchEmploi(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(OffreEmploi::class);
        $requestString=$request->get('searchValue');
        $emploi = $repository->findEmploiParNom($requestString);
        $jsonContent = $Normalizer->normalize($emploi, 'json',['groups'=>'emploi']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }

    /**
     * @Route("/filtreEmploi/{domaine} ", name="filtreEmploi")
     * @param $domaine
     */
    public function filtreEmploi(NormalizerInterface $Normalizer, $domaine)
    {
        $repository = $this->getDoctrine()->getRepository(OffreEmploi::class);

        $emploi = $repository->findDomaine($domaine);
        $jsonContent = $Normalizer->normalize($emploi, 'json',['groups'=>'emploi']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }



}
