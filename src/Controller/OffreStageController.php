<?php

namespace App\Controller;

use App\Entity\OffreStage;
use App\Form\StageType;
use App\Form\StageUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function ShowOffreStage(): Response
    {
        
            $em=$this->getDoctrine()->getRepository(OffreStage::class);
            $list=$em->findAll();
            return $this->render('offre_stage/showOffreStage.html.twig',["l"=>$list]);
        
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
        if ($form->isSubmitted()) {
          //  dd();
            $em = $this->getDoctrine()->getManager();
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
}
