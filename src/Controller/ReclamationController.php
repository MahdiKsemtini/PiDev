<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\AdminReclamtionRepository;
use App\Repository\AdminRepository;
use App\Repository\FreelancerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ReclamationRepository;
use phpDocumentor\Reflection\Types\Integer;

use Symfony\Bridge\Twig\Mime\NotificationEmail;


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
        $session = $request->getSession();

        $reclamation = new Reclamation();
        $freelancer = $repository->findOneBy(['email' =>$session->get('email')]);
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

            return $this->redirectToRoute("forum");
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


    /*  /**
       * @Route("/ReclamationMail",name="ReclamationMail")
       */
    /* public function reclamationMail(\Swift_Mailer $mailer){
         $message=(new \Swift_Message("reclamation"))
             ->setFrom('tt1384648@gmail.com')
             ->setTo('naderbessioud98@gmail.com')
             ->setBody("test",'text/plain');
         $mailer->send($message);
         return($this->render("reclamation/index.html.twig"));
     }*/
}
