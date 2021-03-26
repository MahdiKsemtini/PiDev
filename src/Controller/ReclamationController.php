<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Reclamation;
use App\Form\AvisType;
use App\Form\ReclamationType;
use App\Repository\AdminReclamationRepository;
use App\Repository\AdminRepository;
use App\Repository\FreelancerRepository;
use App\Repository\ReclamationRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Knp\Component\Pager\PaginatorInterface;


class ReclamationController extends AbstractController
{


    /**
     * @Route("/showReclamation", name="showReclamation")
     */
    public function ShowReclamation(PaginatorInterface $paginator, Request $request): Response
    {

        $em = $this->getDoctrine()->getRepository(Reclamation::class);
        $list = $em->findAll();
        $list = $paginator->paginate(
        // Doctrine Query, not results
            $list,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('reclamation/AfficherReclamation.html.twig', ["l" => $list]);

    }


    /**
     * @Route("/ajouterReclamation", name="ajouterReclamation")
     * @param Request $request
     * @param AdminRepository $adminRepository
     * @param AdminReclamationController $adminReclamationController
     * @param FreelancerRepository $repository
     * @param ReclamationRepository $reclamationRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    public function addReclamation(\Symfony\Component\HttpFoundation\Request $request,AdminRepository $adminRepository,AdminReclamationController $adminReclamationController,FreelancerRepository $repository, ReclamationRepository $reclamationRepository)
    {

        $reclamation = new Reclamation();
        $freelancer = $repository->findOneBy(['email' =>"fffffff@fffffff"]);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $reclamation->setEmailUtilisateur($freelancer->getEmail());
            $reclamation->setNomUtilisateur($freelancer->getNom());
            $newDate= new \DateTime('now');
            $reclamation->setDateReclamation($newDate->format('Y-m-d H:i:s'));
            $reclamation->setEtat(0);


            $em = $this->getDoctrine()->getManager();


            $NonApprouve = $reclamationRepository->countReclamtionNonApprouve();

            $admins = $adminRepository->findBy(array('type'=>'Admin des reclamations'));
            $entitymanager = $this->getDoctrine()->getManager();
            foreach ($NonApprouve as $count){
                foreach ($admins as $admin) {
                    $adminRepository->find($admin->getId())->setNonapprouve(((integer)$count['count']) + 1);

                }
            }
            $em->persist($reclamation);
            $em->flush();




            $adminReclamationController->ReclamationToAdmin($adminRepository, $reclamation->getId(), $freelancer,$reclamationRepository);

            return $this->redirectToRoute("ajouterReclamation");
        }
        return $this->render("reclamation/AjouterReclamation.html.twig", [
            'f' => $form->createView(),

        ]);
    }
}