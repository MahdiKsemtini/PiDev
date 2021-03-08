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
use Knp\Component\Pager\PaginatorInterface;

class CommentairesController extends AbstractController
{
    /**
     * @Route("/commentaires{id_pub}",name="commentaires")
     */
    public function afficherCommentaires(Request $request, $id_pub): Response
    {
        $em=$this->getDoctrine()->getManager();
        $com=$em->getRepository(Commentaires::class)->findBy(['id_pub'=> $id_pub]);
        $id=$id_pub;
        $publications=$em->getRepository(Publications::class)->find($id);
        $commentaire = new Commentaires();
        $commentaire->setIdPub($publications);
        $commentaire->setDateCom(new \DateTime('now'));


        $form1 = $this->createForm(CommentairesType::class, $commentaire);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() and $form1->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();


            return $this->redirect('forum');
        }

        return $this->render('Front/publications/commentaires.html.twig', array('commentaires'=>$com,'form' => $form1->createView()));
    }

    /**
     * @Route("/backCommentaires",name="backCommentaires")
     */
    public function list_commentaires(Request $request, PaginatorInterface $paginator): Response
    {
        $p=$this->getDoctrine()->getRepository(Commentaires::class);
        $commentaires=$p->findAll();
        $coms = $paginator->paginate($commentaires, $request->query->getInt('page', 1), 3);

        return $this->render('Back/publications/backCommentaires.html.twig', array('commentaires'=>$coms));
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
     * @Route("/supprimerCommentaire/{id}",name="supprimerCommentaire")
     */
    public function supprimerCommentaire($id){
        $em=$this->getDoctrine()->getManager();
        $commentaires=$em->getRepository(Commentaires::class)->find($id);
        $id_pub=$commentaires->getIdPub();
        $em->remove($commentaires);
        $em->flush();
        return $this->redirectToRoute('forum');

    }

    /**
     * @Route("/editcom{id}",name="modifiercommentaire")
     */
    public function modifierCommentaire(Request $request,int $id)
    {

        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->find($id);
        $id_pub=$commentaires->getIdPub();
        $form = $this->createForm(CommentairesType::class, $commentaires);

        $form = $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaires);
            $em->flush();
            return $this->redirectToRoute('forum');
        }
        return $this->render('Front/publications/editcom.html.twig', array('form' => $form->createView()));

    }
}
