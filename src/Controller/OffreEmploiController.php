<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Form\EmploiType;
use App\Form\EmploiUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Knp\Component\Pager\PaginatorInterface;

class OffreEmploiController extends AbstractController
{


    /**
     * @Route("/showEmploi", name="showEmploi")
     */
    public function ShowOffreEmploi(): Response
    {

        $em=$this->getDoctrine()->getRepository(OffreEmploi::class);
        $list=$em->findAll();
        return $this->render('offre_emploi/showOffreEmploi.html.twig',["l"=>$list]);

    }

    /**
     * @Route("/addEmploi", name="addEmploi")
     * @param Request $request
     */
    public function addEmploi(Request $request){
        $emploi = new OffreEmploi();
        $form = $this->createForm(EmploiType::class, $emploi);
        // $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
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
}
