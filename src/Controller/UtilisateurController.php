<?php

namespace App\Controller;

use App\Entity\Freelancer;
use App\Entity\Societe;
use App\Form\FreelancerProfileType;
use App\Form\FreelancerSignInType;
use App\Form\FreelancerSignUpType;
use App\Form\SocieteSignUpType;
use App\Repository\FreelancerRepository;
use App\Repository\SocieteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }


    /**
     * @param Request $request
     * @Route("/SignIn", name="SignIn")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function SignIn(Request $request,FreelancerRepository $repository,SocieteRepository $Sos_Repo): Response
    {
        $freelancer=new Freelancer();
        $societe=new Societe();
        $form=$this->createForm(FreelancerSignInType::class,$freelancer);
        $form->handleRequest($request);


        if($form->isSubmitted()) {
            $freelancerCheck = $repository->findOneBy(['email' => $freelancer->getEmail()]);
            $societeCheck = $Sos_Repo->findOneBy(['email' => $freelancer->getEmail()]);
            if ($freelancerCheck != null) {

                $session= new Session();

                $session->set('id',$freelancerCheck->getId());
                $session->set('nom',$freelancerCheck->getNom());
                $session->set('prenom',$freelancerCheck->getPrenom());
                $session->set('email',$freelancerCheck->getEmail());
                $session->set('adresse',$freelancerCheck->getAdresse());
                $session->set('competences',$freelancerCheck->getCompetences());
                $session->set('compte_reseau_sociaux',$freelancerCheck->getComptesReseauxSociaux());
                $session->set('langues',$freelancerCheck->getLangues());
                $session->set('photo_de_profile',$freelancerCheck->getPhotoDeProfile());
                $session->set('sexe',$freelancerCheck->getSexe());
                return $this->redirectToRoute('ProfileFreelancer');
//                return $this->redirectToRoute('Profile',array(
//                    'id'=>$freelancerCheck->getId(),
//                    'nom' => $freelancerCheck->getNom(),
//                    'prenom'=>$freelancerCheck->getPrenom(),
//                    'email'=>$freelancerCheck->getEmail(),
//                    'adresse'=>$freelancerCheck->getAdresse(),
//                    'competences'=>$freelancerCheck->getCompetences(),
//                    'compte_reseau_sociaux'=>$freelancerCheck->getComptesReseauxSociaux(),
//                    'langues'=>$freelancerCheck->getLangues(),
//                    'photo_de_profile'=>$freelancerCheck->getPhotoDeProfile(),
//                    'sexe'=>$freelancerCheck->getSexe(),
//                ));
                echo('freelancer exist');
            } elseif ($societeCheck != null){
                echo('societe exist');
            } else {
                echo('email doesnt exist');
            }
        }

        return $this->render('utilisateur/Login.html.twig', [
            'controller_name' => 'UtilisateurController',
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/SignUp/{type}", name="SignUp")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function SignUp(Request $request,$type,FreelancerRepository $repository)
    {
        if($type == "Freelancer")
        {
            $freelancer=new Freelancer();
            $form=$this->createForm(FreelancerSignUpType::class,$freelancer);
            $form->handleRequest($request);

            $product = $repository->findOneBy(['email' => $freelancer->getEmail()]);
            if($product!=null){
                echo($product->getEmail());
            }elseif ($product==null){
                if($form->isSubmitted()){
                    $freelancer->setAdresse('Add Adresse');
                    $freelancer->setCompetences('Add Competence');
                    $freelancer->setComptesReseauxSociaux('Add Reseaux Sociaux');
                    $freelancer->setLangues('Add Langues');
                    $freelancer->setSexe('Add sexe');
                    $freelancer->setPhotoDeProfile('Add PhotoDeProfile');
                    //get the entity manager that exists in doctrine( entity manager and repository)
                    $em=$this->getDoctrine()->getManager();
                    // tell Doctrine you want to (eventually) save the Product (no queries yet)
                    $em->persist($freelancer);
                    // actually executes the queries
                    $em->flush();
                    // return to the affiche
                    return $this->redirectToRoute('SignIn');
                }
            }

        }else
        {
            $societe=new Societe();
            $form=$this->createForm(SocieteSignUpType::class,$societe);

            $form->handleRequest($request);

            $product = $repository->findOneBy(['email' => $societe->getEmail()]);
            if($product!=null){
                echo("il 3aaaaaa");
            }elseif ($product==null){
                if($form->isSubmitted()){
                    echo("aaaaaa");
                    $societe->setAdresse('Add Adresse');
                    $societe->setStatusJuridique('Add Status Juridique');
                    $societe->setPhotoDeProfile('Add PhotoDeProfile');

                    //get the entity manager that exists in doctrine( entity manager and repository)
                    $em=$this->getDoctrine()->getManager();
                    // tell Doctrine you want to (eventually) save the Product (no queries yet)
                    $em->persist($societe);
                    // actually executes the queries
                    $em->flush();
                    // return to the affiche
                    return $this->redirectToRoute('SignIn');
                }
            }

        }


        return $this->render('utilisateur/SignUp.html.twig', [
            'controller_name' => 'UtilisateurController',
            'form'=>$form->createView(),
            'type'=>$type
        ]);
    }

    /**
     * @Route("/ForgetPassword", name="ForgetPassword")
     */
    public function ForgetPassword(): Response
    {
        return $this->render('utilisateur/ForgerPassword.htm.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @param Request $request
     * @Route("/ProfileFreelancer", name="ProfileFreelancer")
     */
    public function Profile(FreelancerRepository $repository,Request $request): Response
    {
        $freelancer=$repository->find($this->get('session')->get('id'));
        $form=$this->createForm(FreelancerProfileType::class,$freelancer);
        $form->handleRequest($request);

            if($form->isSubmitted()){
                //get the entity manager that exists in doctrine( entity manager and repository)
                $em=$this->getDoctrine()->getManager();
                // actually executes the queries
                $em->flush();
                // return to the affiche
            }
        return $this->render('utilisateur/FreelancerProfile.html.twig', [
            'controller_name' => 'UtilisateurController',
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/Deconnect", name="Deconnect")
     */
    public function Deconnection(FreelancerRepository $repository,Request $request): Response
    {

        return $this->redirectToRoute('SignIn');
    }



}
