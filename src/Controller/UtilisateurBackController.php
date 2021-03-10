<?php

namespace App\Controller;

use App\Entity\Freelancer;
use App\Entity\Reviews;
use App\Entity\Societe;
use App\Repository\FreelancerRepository;
use App\Repository\ReviewsRepository;
use App\Repository\SocieteRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurBackController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("/utilisateur/back/{type}", name="utilisateur_back")
     */
    public function index($type,FreelancerRepository $freelancerRepository, SocieteRepository $societeRepository,PaginatorInterface $paginator,Request $request): Response
    {
        if($type=='Freelancer')
        {
            $freelancer = new Freelancer();
            $searchForm = $this->createForm(\App\Form\SearchType::class,$freelancer);
            $searchForm->handleRequest($request);
            if ($searchForm->isSubmitted()) {
                $nom = $searchForm['nom']->getData();
                $donnees = $freelancerRepository->search($nom);
                return $this->redirectToRoute('searchFreelancer', array('nom' => $nom));
            }

            $list=$freelancerRepository->findBy([],['nom' => 'desc']);

            // Paginate the results of the query
            $fre = $paginator->paginate(
            // Doctrine Query, not results
                $list,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                4
            );

        }
        else{
            $societe = new Societe();
            $searchForm = $this->createForm(\App\Form\SearchSocieteType::class,$societe);
            $searchForm->handleRequest($request);
            if ($searchForm->isSubmitted()) {
                $nom = $searchForm['nom']->getData();
                $donnees = $societeRepository->search($nom);
                return $this->redirectToRoute('searchSociete', array('nom' => $nom));
            }

            $list=$societeRepository->findAll();

            // Paginate the results of the query
            $fre = $paginator->paginate(
            // Doctrine Query, not results
                $list,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                4
            );

        }
        return $this->render('utilisateur_back/index.html.twig', [
            'controller_name' => 'UtilisateurBackController',
            'list'=>$fre,
            'search'=>$searchForm->createView(),
            'type'=>$type,
        ]);
    }


    /**
     * @param Request $request
     * @Route("/ProfileF/{id}", name="ViewFreelancerProfile")
     */
    public function ViewFProfile($id,FreelancerRepository $freelancerRepository, SocieteRepository $societeRepository): Response
    {
        $freelancer=$freelancerRepository->find($id);
        $freelancer->setViewsNb($freelancer->getViewsNb()+1);
        $this->get('session')->set('viewsNb',$freelancer->getViewsNb());
        $em=$this->getDoctrine()->getManager();
        // actually executes the queries
        $em->flush();

        return $this->render('utilisateur_back/ViewProfile.html.twig', [
            'controller_name' => 'UtilisateurBackController',
            'profile'=>$freelancer,
            'type'=>'Freelancer'

        ]);
    }

    /**
     * @param Request $request
     * @Route("/ProfileS/{id}", name="ViewSocieteProfile")
     */
    public function ViewSProfile($id,FreelancerRepository $freelancerRepository, SocieteRepository $societeRepository): Response
    {
        $societe=$societeRepository->find($id);


        return $this->render('utilisateur_back/ViewProfile.html.twig', [
            'controller_name' => 'UtilisateurBackController',
            'profile'=>$societe,
            'type'=>'Societe'

        ]);
    }

    /**
     * @Route("/ratingFreelancer/{numb}?{idTaker}?{id}", name="ratingFreelancer")
     */
    public function Rating(ReviewsRepository $repository,$numb,$idTaker,$id)
    {
        $review=$repository->findOneBy(['idTaker'=>$idTaker,'idGiver'=>7]);
        if($review!=null){
            $review->setNumberReviews($numb);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
        }else{
            $reviewInstance=new Reviews();
            $reviewInstance->setIdGiver(7);
            $reviewInstance->setIdTaker($idTaker);
            $reviewInstance->setNumberReviews($numb);
            $em=$this->getDoctrine()->getManager();
            $em->persist($reviewInstance);
            $em->flush();
        }
        return $this->redirectToRoute('ViewFreelancerProfile', array('id'=>$id));
    }

    /**
     * @Route("/ratingSocite/{numb}?{idTaker}?{id}", name="ratingSocite")
     */
    public function RatingSos(ReviewsRepository $repository,$numb,$idTaker,$id)
    {
        $review=$repository->findOneBy(['idTaker'=>$idTaker,'idGiver'=>7]);
        if($review!=null){
            $review->setNumberReviews($numb);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
        }else{
            $reviewInstance=new Reviews();
            $reviewInstance->setIdGiver(7);
            $reviewInstance->setIdTaker($idTaker);
            $reviewInstance->setNumberReviews($numb);
            $em=$this->getDoctrine()->getManager();
            $em->persist($reviewInstance);
            $em->flush();
        }
        return $this->redirectToRoute('ViewSocieteProfile', array('id'=>$id));
    }

    /**
     * @param Request $request
     * @Route("/searchFreelancer/{nom}", name="searchFreelancer", methods={"GET","POST"})
     */
    public function searchFreelancer(FreelancerRepository $freelancerRepository,$nom,Request $request): Response
    {
        $freelancer = new Freelancer();
        $searchForm = $this->createForm(\App\Form\SearchType::class,$freelancer);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $nom = $searchForm['nom']->getData();
            $donnees = $freelancerRepository->search($nom);
            return $this->redirectToRoute('searchFreelancer', array('nom' => $nom));
        }
        $free = $freelancerRepository->search($nom);
        return $this->render('utilisateur_back/index.html.twig', [
            'search' => $searchForm->createView(),
            'list'=>$free,
            'type'=>'Freelancer'
        ]);
    }

    /**
     * @param Request $request
     * @Route("/searchSociete/{nom}", name="searchSociete", methods={"GET","POST"})
     */
    public function searchSociete(SocieteRepository $societeRepository,$nom,Request $request): Response
    {
        $societe = new Societe();
        $searchForm = $this->createForm(\App\Form\SearchSocieteType::class,$societe);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $nom = $searchForm['nom']->getData();
            $donnees = $societeRepository->search($nom);
            return $this->redirectToRoute('searchSociete', array('nom' => $nom));
        }
        $soc = $societeRepository->search($nom);
        return $this->render('utilisateur_back/index.html.twig', [
            'search' => $searchForm->createView(),
            'list'=>$soc,
            'type'=>'Societe'
        ]);
    }

    /**
     * @param Request $request
     * @Route("/deactivateFreelancer/{id}", name="deactivateFreelancer", methods={"GET","POST"})
     */
    public function Deactivate(FreelancerRepository $freelancerRepository,$id,Request $request): Response
    {
        $societe=$freelancerRepository->find($id);
        $societe->setEtat(1);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('utilisateur_back', array('type' => 'Freelancer'));

    }

    /**
     * @param Request $request
     * @Route("/activateFreelancer/{id}", name="activateFreelancer", methods={"GET","POST"})
     */
    public function Activate(FreelancerRepository $freelancerRepository,$id,Request $request): Response
    {
        $societe=$freelancerRepository->find($id);
        $societe->setEtat(0);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('utilisateur_back', array('type' => 'Freelancer'));

    }


    /**
     * @Route("/pdflist/{type}", name="pdflist")
     */
    public function Pdflist(FreelancerRepository $freelancerRepository,$type,SocieteRepository $societeRepository): Response
    {
        if($type=="Freelancer")
        {
            $freelancer=$freelancerRepository->findAll();
            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');

            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('utilisateur_back/Freelancers_list.html.twig', [
                'list'=>$freelancer,
                'type'=>'Freelancer'
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
        else{
            $societe=$societeRepository->findAll();
            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');

            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('utilisateur_back/Freelancers_list.html.twig', [
                'list'=>$societe,
                'type'=>'societe'
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
}
