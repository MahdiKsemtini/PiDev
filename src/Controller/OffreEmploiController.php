<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Form\EmploiType;
use App\Repository\AdminEmploiRepository;
use App\Repository\AdminRepository;
use App\Repository\OffreEmploiRepository;
use App\Repository\SocieteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OffreEmploiController extends AbstractController
{
    /**
     * @Route("/addEmploi", name="addEmploi")
     * @param Request $request
     * @param OffreEmploiRepository $emploiRepository
     * @param AdminRepository $adminRepository
     * @param AdminEmploiController $adminEmploiController
     * @param SocieteRepository $societeRepository
     * @param AdminEmploiRepository $adminEmploiRepository
     * @return RedirectResponse|Response
     */
    public function addEmploi(Request $request, OffreEmploiRepository $emploiRepository,AdminRepository $adminRepository,AdminEmploiController $adminEmploiController,SocieteRepository $societeRepository,AdminEmploiRepository $adminEmploiRepository){

        $emploi = new OffreEmploi();
        //   $emploi->setFichier(($baseurl . "/uploads/cantine/" . $newFilename));
        $form = $this->createForm(EmploiType::class, $emploi);
        // $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            //  dd();
            $em = $this->getDoctrine()->getManager();
            $newDate= new \DateTime('now');
            $emploi->setDateCreation($newDate);
            $emploi->setEtat(0);
            $societe = $societeRepository->find(1);
            $emploi->setSociete($societe);


            $NonApprouve = $emploiRepository->countOffreEmploiNonApprouve();

            $admins = $adminRepository->findBy(array('type'=>'Admin des emplois'));

            foreach ($NonApprouve as $count) {
                foreach ($admins as $admin) {
                    $adminRepository->find($admin->getId())->setNonapprouve(((integer)$count['count']) + 1);

                }
            }


                $em->persist($emploi);
            $em->flush();


            $societeemail = $societeRepository->find($emploi->getSociete());
            $adminEmploiController->OffreEmploiToAdmin($adminRepository,$emploi->getId(),$societeemail->getEmail(),$emploiRepository,$adminEmploiRepository);

            return $this->redirectToRoute('showEmploi');
        }
        return $this->render('offre_emploi/CreateOffreEmploi.html.twig', [
            "f" => $form->createView(),
        ]);



    }

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
     * @Route("/deleteOwnEmploi/{id}", name="deleteOwnEmploi")
     */
    public function deleteOwnEmploi($id)
    {
        $em=$this->getDoctrine()->getManager();
        $offre=$em->getRepository(OffreEmploi::class)->find($id);
        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute("showuown");
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
     * @Route("/editOwnEmploi/{id}", name="editOwnEmploi")
     */
    public function editOwnEmploi(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $emploi = $em->getRepository(OffreEmploi::class)->find($id);
        $form = $this->createForm(EmploiUpdateType::class, $emploi);
        //  $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            //   $em->persist($emploi);
            $em->flush();
            return $this->redirectToRoute('showuown');
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
     * @Route("/showOwnEmploi/{id}", name="showuown")
     */
    public function ShowOwnOffre($id): Response
    {

        $em=$this->getDoctrine()->getRepository(OffreEmploi::class);
        $list=$em->findBy(array('societe'=>$id));
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
        //  $pdfOptions->setIsRemoteEnabled(true);

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

        // $dompdf->output();
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
    /**
     * @Route("/updateExpiration ", name="updateExpiration")
     *
     */
    public function updateExpiration(OffreEmploiRepository $offreEmploiRepository): Response
    {
        $offreEmploiRepository->updateDate();
        return $this->redirectToRoute('showEmploi');
    }


}
