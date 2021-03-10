<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Reclamation;
use App\Form\AvisType;
use App\Form\ReclamationType;
use App\Repository\AdminReclamationRepository;
use App\Repository\AdminRepository;
use App\Repository\FreelancerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{


    /**
     * @Route("/showReclamation", name="showReclamation")
     */
    public function ShowReclamation(): Response
    {

        $em = $this->getDoctrine()->getRepository(Reclamation::class);
        $list = $em->findAll();
        return $this->render('reclamation/AfficherReclamation.html.twig', ["l" => $list]);

    }

    /**
     * @Route("/ajouterReclamation", name="ajouterReclamation")
     * @param Request $request
     */

    public function addReclamation(\Symfony\Component\HttpFoundation\Request $request,AdminRepository $adminRepository,AdminReclamationController $adminReclamationController,FreelancerRepository $repository)
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
            $em->persist($reclamation);
            $em->flush();
            $adminReclamationController->ReclamationToAdmin($adminRepository,$reclamation->getId());
            return $this->redirectToRoute("showReclamation");
        }
        return $this->render("reclamation/AjouterReclamation.html.twig", [
            'f' => $form->createView(),

        ]);
    }
    /**
     * @param Request $request
     * @Route ("/updateReclamation/{id}" , name="updateReclamation")
     */
    public function updateReclamation (Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation =$em->getRepository(Reclamation::class)->find($id);
        $form = $this->createForm(ReclamationType::class ,$reclamation);

        $form->handleRequest($request);
        if ($form->isSubmitted()){

            $em->flush();
            return $this->redirectToRoute("showReclamation");
        }
        return $this->render('reclamation/updateReclamation.html.twig', ['f' => $form->createView()]);
    }
    /**
     * @Route("/deleteReclamation/{id}", name="deleteReclamation")
     */
    public function deleteReclamation($id)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository(Reclamation::class)->find($id);
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute("showReclamation");
    }
}