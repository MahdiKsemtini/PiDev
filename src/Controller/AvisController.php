<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Reclamation;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\FreelancerRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="avis")
     */
    public function index(Request $request,FreelancerRepository $repository): Response
    {
        $avis= new Avis();
        $freelancer = $repository->findOneBy(['email' =>"fffffff@fffffff"]);
        $form= $this->createForm(AvisType::class,$avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            $avis->setEmailUtilisateur($freelancer->getEmail());
            $avis->setNomUtilisateur($freelancer->getNom());
            $em=$this->getDoctrine()->getManager();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($avis);
            // actually executes the queries
            $em->flush();
            // return to the affiche
            return $this->redirectToRoute('showAvis');
        }
        return $this->render('avis/Avis.html.twig', [
            'controller_name' => 'AvisController',
            'nom'=>$freelancer->getNom(),
            'form'=>$form->createView()

        ]);
    }
    /**
     * @Route("/showAvis", name="showAvis")
     */
    public function ShowAvis(): Response
    {

        $em=$this->getDoctrine()->getRepository(Avis::class);
        $list=$em->findAll();
        return $this->render('avis/AfficherAvis.html.twig',["l"=>$list]);

    }
    /**
     * @param Request $request
     * @Route ("/updateAvis/{idAvis}" , name="updateAvis")
     */
    public function updateAvis (Request $request, $idAvis)
    {
        $em=$this->getDoctrine()->getManager();
        $avis =$em->getRepository(Avis::class)->find($idAvis);
        $form = $this->createForm(AvisType::class ,$avis);

        $form->handleRequest($request);
        if ($form->isSubmitted()){

            $em->flush();
            return $this->redirectToRoute("showAvis");
        }
        return $this->render('avis/updateAvis.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/deleteAvis/{id}", name="deleteAvis")
     */
    public function deleteAvis($id)
    {
        $em=$this->getDoctrine()->getManager();
        $avis=$em->getRepository(Avis::class)->find($id);
        $em->remove($avis);
        $em->flush();
        return $this->redirectToRoute("showAvis");
    }
}
