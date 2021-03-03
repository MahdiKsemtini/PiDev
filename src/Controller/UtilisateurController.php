<?php

namespace App\Controller;

use App\Entity\Freelancer;
use App\Entity\Societe;
use App\Form\FreelancerProfileType;
use App\Form\FreelancerSignInType;
use App\Form\FreelancerSignUpType;
use App\Form\SocieteProfileType;
use App\Form\SocieteSignUpType;
use App\Repository\FreelancerRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
                if($freelancer->getMotDePasse()==$freelancerCheck->getMotDePasse()){
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
                }else{
                    echo '<script>alert("Mot de pass est Incorrect")</script>';

                }


            } elseif ($societeCheck != null){
                if ($freelancer->getMotDePasse()==$societeCheck->getMotDePass()){
                    $session= new Session();
                    $session->set('id',$societeCheck->getId());
                    $session->set('nom',$societeCheck->getNom());
                    $session->set('email',$societeCheck->getEmail());
                    $session->set('adresse',$societeCheck->getAdresse());
                    $session->set('status_juridique',$societeCheck->getStatusJuridique());
                    $session->set('photo_de_profile',$societeCheck->getPhotoDeProfile());
                    return $this->redirectToRoute('ProfileSociete');
                }
                else{
                    echo '<script>alert("Mot de pass est Incorrect")</script>';
                }

            } elseif($societeCheck == null){
                echo '<script>alert("Email n\'exist pas")</script>';
            }
            elseif($freelancerCheck == null){
                echo '<script>alert("Email n\'exist pas")</script>';
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
    public function SignUp(Request $request,$type,FreelancerRepository $repository, EntityManagerInterface $em)
    {
        if($type == "Freelancer")
        {
            $freelancer=new Freelancer();
            $form=$this->createForm(FreelancerSignUpType::class,$freelancer);
            $form->handleRequest($request);

            $freelancer->setAdresse('Add Adresse');
            $freelancer->setCompetences('Add Competence');
            $freelancer->setComptesReseauxSociaux('Add Reseaux Sociaux');
            $freelancer->setLangues('Add Langues');
            $freelancer->setSexe('Add sexe');
            $freelancer->setPhotoDeProfile('Add PhotoDeProfile');

            $free = $repository->findOneBy(['email' => $freelancer->getEmail()]);
            if($free!=null){
                echo '<script>alert("Email deja exist")</script>';
            }elseif ($free==null){

                if($form->isSubmitted()){

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

            $soci = $repository->findOneBy(['email' => $societe->getEmail()]);
            if($soci!=null){
                echo '<script>alert("Email deja exist")</script>';
            }elseif ($soci==null){
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
    public function ProfileFreelancer(FreelancerRepository $repository,Request $request): Response
    {
        $freelancer=$repository->find($this->get('session')->get('id'));
        $form=$this->createForm(FreelancerProfileType::class,$freelancer);
        $form->handleRequest($request);

            if($form->isSubmitted()){

                $uploadedFile = $form['photo_de_profile']->getData();
                $filename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                $uploadedFile->move($this->getParameter('upload_directory'),$filename);
                $freelancer->setPhotoDeProfile($filename);

                $this->get('session')->set('id',$freelancer->getId());
                $this->get('session')->set('nom',$freelancer->getNom());
                $this->get('session')->set('prenom',$freelancer->getPrenom());
                $this->get('session')->set('email',$freelancer->getEmail());
                $this->get('session')->set('adresse',$freelancer->getAdresse());
                $this->get('session')->set('competences',$freelancer->getCompetences());
                $this->get('session')->set('compte_reseau_sociaux',$freelancer->getComptesReseauxSociaux());
                $this->get('session')->set('langues',$freelancer->getLangues());
                $this->get('session')->set('photo_de_profile',$freelancer->getPhotoDeProfile());
                $this->get('session')->set('sexe',$freelancer->getSexe());
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
     * @Route("/ProfileSociete", name="ProfileSociete")
     */
    public function ProfileSociete(SocieteRepository $repository,Request $request): Response
    {
        $societe=$repository->find($this->get('session')->get('id'));
        $form=$this->createForm(SocieteProfileType::class,$societe);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            $session= new Session();
            $this->get('session')->set('id',$societe->getId());
            $this->get('session')->set('nom',$societe->getNom());
            $this->get('session')->set('email',$societe->getEmail());
            $this->get('session')->set('adresse',$societe->getAdresse());
            $this->get('session')->set('status_juridique',$societe->getStatusJuridique());
            $this->get('session')->set('photo_de_profile',$societe->getPhotoDeProfile());
            //get the entity manager that exists in doctrine( entity manager and repository)
            $em=$this->getDoctrine()->getManager();
            // actually executes the queries
            $em->flush();
            // return to the affiche
        }
        return $this->render('utilisateur/SocieteProfile.html.twig', [
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
