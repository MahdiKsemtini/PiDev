<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Publications;
use App\Form\CommentairesType;
use App\Form\PublicationsType;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CommentairesController extends AbstractController
{
    /**
     * @Route("/commentaires{id_pub}",name="commentaires")
     */
    public function afficherCommentaires($id_pub): Response
    {
        $em=$this->getDoctrine()->getManager();
        $commentaires=$em->getRepository(Commentaires::class)->findBy(['id_pub'=> $id_pub]);

        return $this->render('Front/publications/commentaires.html.twig', array('commentaires'=>$commentaires));
    }

    /**
     * @Route("/backCommentaires",name="backCommentaires")
     */
    public function list_commentaires(): Response
    {
        $p=$this->getDoctrine()->getRepository(Commentaires::class);
        $commentaires=$p->findAll();

        return $this->render('Back/publications/backCommentaires.html.twig', array('commentaires'=>$commentaires));
    }

    /**
     * @Route("/deleteCommentaire/{id}",name="deleteCommentaire")
     */
    public function deleteCommentaire($id){
        $em=$this->getDoctrine()->getManager();
        $commentaires=$em->getRepository(Commentaires::class)->find($id);


        $em->remove($commentaires);
        $em->flush();
        return $this->redirectToRoute('backCommentaires');

    }

    /**
     * @Route("/addcom{id}",name="ajoutercom")
     */
    public function ajouterCommentaire(Request $request, $id)
    {
        $publications = $this->getDoctrine()->getRepository(Publications::class)->find($id);
        $commentaire = new Commentaires();
        $commentaire->setIdPub($id);
        $commentaire->setDateCom(new \DateTime('now'));


        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();


            return $this->redirect('forum');
        }
        return $this->render('Front/publications/addcom.html.twig', array('form' => $form->createView()));

    }




    /**
     * @Route("/supprimerCommentaire/{id}",name="supprimerCommentaire")
     */
    public function supprimerCommentaire($id){
        $em=$this->getDoctrine()->getManager();
        $commentaires=$em->getRepository(Commentaires::class)->find($id);
        $id_pub=$commentaires->getIdPub();
        $em->remove($commentaires);
        $em->flush();
        return $this->redirectToRoute('commentaires', ['id_pub' => $id_pub]);

    }

    /**
     * @Route("/editcom{id}",name="modifiercommentaire")
     */
    public function modifierCommentaire(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->find($id);
        $id_pub=$commentaires->getIdPub();
        $form = $this->createForm(CommentairesType::class, $commentaires);

        $form = $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaires);
            $em->flush();
            return $this->redirectToRoute('commentaires', ['id_pub' => $id_pub]);
        }
        return $this->render('Front/publications/editcom.html.twig', array('form' => $form->createView()));

    }
}
