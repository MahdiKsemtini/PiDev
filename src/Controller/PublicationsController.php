<?php

namespace App\Controller;

use App\Entity\Publications;
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

class PublicationsController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index(): Response
    {
        $publications = $this->getDoctrine()->getRepository(Publications::class)->findAll();
        return $this->render('publications/forum.html.twig', array('publications'=>$publications));
    }
    /**
     * @Route("/forumedit", name="forumedit")
     */
    public function modifier_publication(): Response
    {
        return $this->render('publications/forumedit.html.twig', [
            'controller_name' => 'PublicationsController',
        ]);
    }

    /**
     * @Route("/forum", name="forum")
     */
    public function ajouterPublication(Request $request)
    {

        $publication = new Publications();
        $publication->setDatePublication(new \DateTime('now'));

        $form = $this->createForm(PublicationsType::class, $publication);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $file = $publication->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$filename);
            $publication->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('forum');
        }
        return $this->render('publications/forum.html.twig', array('f' => $form->createView()));

    }



}
