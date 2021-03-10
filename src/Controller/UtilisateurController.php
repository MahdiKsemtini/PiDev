<?php

namespace App\Controller;

use App\Entity\Freelancer;
use App\Entity\Reviews;
use App\Entity\Societe;
use App\Form\FreelancerProfileType;
use App\Form\FreelancerSignInType;
use App\Form\FreelancerSignUpType;
use App\Form\SocieteProfileType;
use App\Form\SocieteSignUpType;
use App\Repository\FreelancerRepository;
use App\Repository\ReviewsRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

use Dompdf\Dompdf;
use Dompdf\Options;
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

        $session = $request->getSession();

        if($session->get('id')!=null){
            if($session->get('compte_facebook')!=null){
                return $this->redirectToRoute('ProfileFreelancer');
            }
            else{
                return $this->redirectToRoute('ProfileSociete');
            }
        }
        else{
            $freelancer=new Freelancer();
            $societe=new Societe();
            $form=$this->createForm(FreelancerSignInType::class,$freelancer);
            $form->handleRequest($request);


            if($form->isSubmitted()) {
                $freelancerCheck = $repository->findOneBy(['email' => $freelancer->getEmail()]);
                $societeCheck = $Sos_Repo->findOneBy(['email' => $freelancer->getEmail()]);
                if ($freelancerCheck != null) {
                    if($freelancer->getMotDePasse()==$freelancerCheck->getMotDePasse()){
                        if($freelancerCheck->getEtat()==0){
                            $session= new Session();

                            $session->set('id',$freelancerCheck->getId());
                            $session->set('nom',$freelancerCheck->getNom());
                            $session->set('viewsNb',$freelancerCheck->getViewsNb());
                            $session->set('prenom',$freelancerCheck->getPrenom());
                            $session->set('email',$freelancerCheck->getEmail());
                            $session->set('adresse',$freelancerCheck->getAdresse());
                            $session->set('competences',$freelancerCheck->getCompetences());
                            $session->set('compte_facebook',$freelancerCheck->getCompteFacebook());
                            $session->set('compte_linkedin',$freelancerCheck->getCompteLinkedin());
                            $session->set('compte_twitter',$freelancerCheck->getCompteTwitter());
                            $session->set('langues',$freelancerCheck->getLangues());
                            $session->set('photo_de_profile',$freelancerCheck->getPhotoDeProfile());
                            $session->set('sexe',$freelancerCheck->getSexe());

                            return $this->redirectToRoute('ProfileFreelancer');
                        }else{
                            return $this->redirectToRoute('CompteDesactiver');
                        }

                    }else{
                        echo '<script>alert("Mot de pass est Incorrect")</script>';

                    }


                } elseif ($societeCheck != null){
                    if ($freelancer->getMotDePasse()==$societeCheck->getMotDePass()){
                        $session= new Session();
                        $session->set('viewNb',$societeCheck->getViewsNb());
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

    }

    /**
     * @param Request $request
     * @Route("/SignUp/{type}", name="SignUp")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function SignUp(Request $request,$type,FreelancerRepository $repository, EntityManagerInterface $em)
    {
        $session = $request->getSession();

        if($session->get('id')!=null){
            if($session->get('compte_facebook')!=null){
                return $this->redirectToRoute('ProfileFreelancer');
            }
            else{
                return $this->redirectToRoute('ProfileSociete');
            }
        }
        else{
            if($type == "Freelancer")
            {
                $freelancer=new Freelancer();
                $form=$this->createForm(FreelancerSignUpType::class,$freelancer);
                $form->handleRequest($request);

                $freelancer->setAdresse('Add Adresse');
                $freelancer->setCompetences('Add Competence');
                $freelancer->setCompteFacebook('Add Compte Facebook');
                $freelancer->setCompteLinkedin('Add Compte LinkedIn');
                $freelancer->setCompteTwitter('Add Compte Twitter');
                $freelancer->setLangues('Add Langues');
                $freelancer->setViewsNb(0);
                $freelancer->setSexe('Add sexe');
                $freelancer->setPhotoDeProfile('img-1.jpg');

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
                        $societe->setViewsNb(0);
                        $societe->setStatusJuridique('Add Status Juridique');
                        $societe->setPhotoDeProfile('img-1.jpg');

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
    public function ProfileFreelancer(FreelancerRepository $repository,Request $request,ReviewsRepository $reviewsRepository): Response
    {
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }
        else{
            $freelancer=$repository->find($this->get('session')->get('id'));
            $form=$this->createForm(FreelancerProfileType::class,$freelancer);
            $form->handleRequest($request);
            $review=$reviewsRepository->findBy(['idTaker'=>$session->get('id')]);
            $value=0;
            $j=0;
            foreach ($review as $i){
                $j++;
                $value+= $i->getNumberReviews();
            }
            $session->set('NumbReviews',round($value/$j));

            if($form->isSubmitted()){

                $uploadedFile = $form['photo_de_profile']->getData();
                $filename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                $uploadedFile->move($this->getParameter('upload_directory'),$filename);
                $freelancer->setPhotoDeProfile($filename);

                $this->get('session')->set('id',$freelancer->getId());
                $this->get('session')->set('viewsNb',$freelancer->getViewsNb());
                $this->get('session')->set('nom',$freelancer->getNom());
                $this->get('session')->set('prenom',$freelancer->getPrenom());
                $this->get('session')->set('email',$freelancer->getEmail());
                $this->get('session')->set('adresse',$freelancer->getAdresse());
                $this->get('session')->set('competences',$freelancer->getCompetences());
                $this->get('session')->set('compte_facebook',$freelancer->getCompteFacebook());
                $this->get('session')->set('compte_linkedin',$freelancer->getCompteLinkedin());
                $this->get('session')->set('compte_twitter',$freelancer->getCompteTwitter());
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
        ]);}
    }

    /**
     * @param Request $request
     * @Route("/ProfileSociete", name="ProfileSociete")
     */
    public function ProfileSociete(SocieteRepository $repository,Request $request): Response
    {
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }
        else{
            $societe=$repository->find($this->get('session')->get('id'));
            $form=$this->createForm(SocieteProfileType::class,$societe);
            $form->handleRequest($request);
            if($form->isSubmitted()){
                $uploadedFile = $form['photo_de_profile']->getData();
                $filename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                $uploadedFile->move($this->getParameter('upload_directory'),$filename);
                $societe->setPhotoDeProfile($filename);

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

    }

    /**
     * @param Request $request
     * @Route("/Deconnect", name="Deconnect")
     */
    public function Deconnection(FreelancerRepository $repository,Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();
        return $this->redirectToRoute('SignIn');
    }

    /**
     * @param Request $request
     * @Route("/deactivateF", name="deactivateF", methods={"GET","POST"})
     */
    public function Deactivate(FreelancerRepository $freelancerRepository,Request $request): Response
    {
        $session = $request->getSession();
        $societe=$freelancerRepository->find($session->get('id'));
        $societe->setEtat(1);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        $session->clear();
        return $this->redirectToRoute('SignIn');

    }

    /**
     * @param Request $request
     * @Route("/CompteDesactiver", name="CompteDesactiver")
     */
    public function CompteDesactiver(FreelancerRepository $repository,Request $request): Response
    {
        return $this->render('utilisateur/compte_non_activer.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("/CV", name="CV")
     */
    public function CV(FreelancerRepository $repository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('utilisateur/cv.html.twig');

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }




}
