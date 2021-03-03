<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Formation;
use App\Entity\Student;
use App\Form\FormationType;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="formation")
     */
    public function index(): Response
    {
        return $this->render('formation/AjouterFormation.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }


    /**
     * @Route("/Afficherformation/{id}", name="Afficherformation")
     */
    public function AfficherFormation($id){
        $em=$this->getDoctrine()->getRepository(Formation::class);
        $formations=$em->findBy(array('idU' => $id,'Etat'=>1));
        $formations1=$em->findBy(array('Etat'=>1));;
        return $this->render("formation/AfficherFormation.html.twig",['formations'=>$formations,'forms'=>$formations1]);
}
    /**
     * @Route("/Afficherallformation", name="Afficherallformation")
     */
    public function AfficherAllFormation(){
        $em=$this->getDoctrine()->getRepository(Formation::class);
        $formations1=$em->findBy(array('Etat'=>1));;
        return $this->render("formation/AfficherFormation.html.twig",['forms'=>$formations1]);
    }

    /**
     * @Route("/addFormation",name="addFormation")
     */
    public function addFormation(\Symfony\Component\HttpFoundation\Request $request){
        $formation=new Formation();
        $form=$this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $formation->setIdU(0);
            $formation->setEtat(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute("Afficherformation",array('id'=>0));
        }
        return $this->render("formation/AjouterFormation.html.twig",['f'=>$form->createView()]);

    }

    /**
     * @Route("/ModifierFormation/{id}",name="ModifierFormation")
     */
    public function updateStudent($id,\Symfony\Component\HttpFoundation\Request $request){
        $em=$this->getDoctrine()->getManager();
        $formation=$em->getRepository(Formation::class)->find($id);
        $form=$this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $formation->setIdU(0);
            $em->flush();
            return $this->redirectToRoute("Afficherformation",array('id'=>0));
        }
        return $this->render("formation/AjouterFormation.html.twig",['f'=>$form->createView()]);


    }

    /**
     * @Route("/deleteFormation/{id}",name="deleteFormation")
     */
    public function deleteClass($id){
        $em=$this->getDoctrine()->getManager();
        $f=$em->getRepository(Formation::class)->find($id);
        $em->remove($f);
        $em->flush();
        return $this->redirectToRoute("Afficherformation",array('id'=>0));
    }


}
