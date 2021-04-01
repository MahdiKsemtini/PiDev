<?php

namespace App\Controller;

use App\Entity\Freelancer;
use App\Entity\Reclamation;
use App\Entity\Reviews;
use App\Entity\ReviewsTextual;
use App\Entity\Societe;
use App\Form\ChangePassType;
use App\Form\ForgetPassType;
use App\Form\FreelancerProfileType;
use App\Form\FreelancerSignInType;
use App\Form\FreelancerSignUpType;
use App\Form\ReviewsTextType;
use App\Form\SocieteProfileType;
use App\Form\SocieteSignUpType;
use App\Notifications\CreationCompteNotification;
use App\Repository\AdminRepository;
use App\Repository\FreelancerRepository;
use App\Repository\ReviewsRepository;
use App\Repository\ReviewsTextualRepository;
use App\Repository\SocieteRepository;
use App\Repository\SuperAdminRepository;
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
     * @var CreationCompteNotification
     */
    private $notify_creation;


    public function __construct(CreationCompteNotification $notify_creation)
    {
        $this->notify_creation = $notify_creation;
    }

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
    public function SignIn(Request $request,FreelancerRepository $repository,SocieteRepository $Sos_Repo,SuperAdminRepository $superAdminRepository,AdminRepository $adminRepository): Response
    {

        $session = $request->getSession();

        if($session->get('id')!=null){

            if($session->get('compte_facebook')!=null){
                return $this->redirectToRoute('forum');
            }
            elseif($session->get('status_juridique')!=null){
                return $this->redirectToRoute('forum');
            }
            elseif ($session->get('type')=='Admin des emplois'){
                return $this->redirectToRoute('admin_emploi');
            }
            elseif ($session->get('type')=='Admin des events'){
                return $this->redirectToRoute('admin_event');
            }
            elseif ($session->get('type')=='Admin des reclamations'){
                return $this->redirectToRoute('admin_reclamation');
            }
            elseif ($session->get('type')=='super admin'){
                return $this->redirectToRoute('super_admin');
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
                $superAdminCheck = $superAdminRepository->findOneBy(['login' => $freelancer->getEmail()]);
                $adminCheck=$adminRepository->findOneBy(['login' => $freelancer->getEmail()]);
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

                            return $this->redirectToRoute('forum');
                        }else{
                            return $this->redirectToRoute('CompteDesactiver');
                        }

                    }else{
                        echo '<script>alert("Mot de pass est Incorrect")</script>';

                    }


                } elseif ($societeCheck != null){
                    if ($freelancer->getMotDePasse()==$societeCheck->getMotDePass()){
                        if($societeCheck->getEtat()==0) {
                            $session = new Session();
                            $session->set('viewNb', $societeCheck->getViewsNb());
                            $session->set('id', $societeCheck->getId());
                            $session->set('nom', $societeCheck->getNom());
                            $session->set('email', $societeCheck->getEmail());
                            $session->set('adresse', $societeCheck->getAdresse());
                            $session->set('status_juridique', $societeCheck->getStatusJuridique());
                            $session->set('photo_de_profile', $societeCheck->getPhotoDeProfile());
                            return $this->redirectToRoute('forum');
                        }else{
                            return $this->redirectToRoute('CompteDesactiver');
                        }
                    }
                    else{
                        echo '<script>alert("Mot de pass est Incorrect")</script>';
                    }

                }elseif ($superAdminCheck!=null){
                    if ($freelancer->getMotDePasse()==$superAdminCheck->getPassword()){
                        $session= new Session();
                        $session->set('type','super admin');
                        $session->set('id',1);
                        return $this->redirectToRoute('super_admin');
                    }
                    else{
                        echo '<script>alert("Mot de pass est Incorrect")</script>';
                    }
                }elseif ($adminCheck!=null){
                    if ($freelancer->getMotDePasse()==$adminCheck->getPassword()){
                        if($adminCheck->getType()=='Admin des reclamations'){
                            $session= new Session();
                            $session->set('id',$adminCheck->getId());
                            $session->set('nom',$adminCheck->getNom());
                            $session->set('prenom',$adminCheck->getPrenom());
                            $session->set('email',$adminCheck->getLogin());
                            $session->set('type',$adminCheck->getType());
                            return $this->redirectToRoute('admin_reclamation');

                        }elseif ($adminCheck->getType()=='Admin des events'){
                            $session= new Session();
                            $session->set('id',$adminCheck->getId());
                            $session->set('nom',$adminCheck->getNom());
                            $session->set('prenom',$adminCheck->getPrenom());
                            $session->set('type',$adminCheck->getType());
                            return $this->redirectToRoute('admin_event');
                        }else{
                            $session= new Session();
                            $session->set('id',$adminCheck->getId());
                            $session->set('nom',$adminCheck->getNom());
                            $session->set('prenom',$adminCheck->getPrenom());
                            $session->set('type',$adminCheck->getType());
                            return $this->redirectToRoute('admin_emploi');
                        }

                    }
                    else{
                        echo '<script>alert("Mot de pass est Incorrect")</script>';
                    }
                }

                elseif($societeCheck == null){
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
                return $this->redirectToRoute('forum');
            }
            elseif($session->get('status_juridique')!=null){
                return $this->redirectToRoute('forum');
            }
            elseif ($session->get('type')=='Admin des emplois'){
                return $this->redirectToRoute('admin_emploi');
            }
            elseif ($session->get('type')=='Admin des events'){
                return $this->redirectToRoute('admin_pub_event');
            }
            elseif ($session->get('type')=='Admin des reclamations'){
                return $this->redirectToRoute('admin_reclamation');
            }
        }
        else{
            if($type == "Freelancer")
            {
                $freelancer=new Freelancer();
                $form=$this->createForm(FreelancerSignUpType::class,$freelancer);
                $form->handleRequest($request);
                $newDate= new \DateTime('now');
                $freelancer->setAdresse('Add Adresse');
                $freelancer->setDateCreation($newDate->format('Y-m-d H:i:s'));
                $freelancer->setCompetences('Add Competence');
                $freelancer->setCompteFacebook('Add Compte Facebook');
                $freelancer->setCompteLinkedin('Add Compte LinkedIn');
                $freelancer->setCompteTwitter('Add Compte Twitter');
                $freelancer->setLangues('Add Langues');
                $freelancer->setViewsNb(0);
                $freelancer->setEtat(1);
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
                        $this->notify_creation->notifyUser("rightjob.inc@gmail.com",$freelancer->getEmail(),$freelancer->getNom()." ".$freelancer->getPrenom());

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
                        $newDate= new \DateTime('now');
                        $societe->setAdresse('Add Adresse');
                        $societe->setDateCreation($newDate->format('Y-m-d H:i:s'));
                        $societe->setViewsNb(0);
                        $societe->setEtat(1);
                        $societe->setStatusJuridique('Add Status Juridique');
                        $societe->setPhotoDeProfile('img-1.jpg');

                        //get the entity manager that exists in doctrine( entity manager and repository)
                        $em=$this->getDoctrine()->getManager();
                        // tell Doctrine you want to (eventually) save the Product (no queries yet)
                        $em->persist($societe);
                        // actually executes the queries
                        $em->flush();

                        $this->notify_creation->notifyUser("rightjob.inc@gmail.com",$societe->getEmail(),$societe->getNom());

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
    public function ForgetPassword(Request $request, FreelancerRepository $freelancerRepository,SocieteRepository $societeRepository): Response
    {
        $freelancer= new Freelancer();
        $form=$this->createForm(ForgetPassType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $freelancerCheck=$freelancerRepository->findOneBy(['email'=>$form['email']->getData()]);
            $societeCheck=$societeRepository->findOneBy(['email'=>$form['email']->getData()]);
            if(($societeCheck==null) and ($freelancerCheck==null))
            {
                echo '<script>alert("Email n\'exist pas")</script>';
            }else {
                $this->notify_creation->notifyForgetPass("rightjob.inc@gmail.com",$form['email']->getData());
                return $this->redirectToRoute('SignIn');
            }

        }
        return $this->render('utilisateur/ForgerPassword.htm.twig', [
            'controller_name' => 'UtilisateurController',
            'form'=>$form->createView(),

        ]);
    }

    /**
     * @Route("/ChangePassword/{email}", name="ChangePassword")
     */
    public function ChangePassword(Request $request,$email,FreelancerRepository $freelancerRepository,SocieteRepository $societeRepository): Response
    {
        $freelancer= $freelancerRepository->findOneBy(['email' => $email]);
        $societe= $societeRepository->findOneBy(['email'=>$email]);
        if($freelancer!=null)
        {
            $form=$this->createForm(ChangePassType::class,$freelancer);
            $form->handleRequest($request);
            if($form->isSubmitted()){
                if($freelancer->getMotDePasse()==$form['mot_de_passe']->getData()){
                    echo '<script>alert("le mot de passe ne peut pas être le précédent")</script>';
                }else {
                    $freelancer->setMotDePasse($form['mot_de_passe']->getData());
                    $em=$this->getDoctrine()->getManager();
                    // actually executes the queries
                    $em->flush();
                    return $this->redirectToRoute('SignIn');
                }

            }
        }elseif ($societe!=null){
            $form=$this->createForm(ChangePassType::class,$societe);
            $form->handleRequest($request);
            if($form->isSubmitted()){
                if($societe->getMotDePass()==$form['mot_de_passe']->getData()){
                    echo '<script>alert("le mot de passe ne peut pas être le précédent")</script>';
                }else{
                    $societe->setMotDePass($form['mot_de_passe']->getData());
                    $em=$this->getDoctrine()->getManager();
                    // actually executes the queries
                    $em->flush();
                    return $this->redirectToRoute('SignIn');
                }
            }
        }

        return $this->render('utilisateur/ChangePassword.html.twig', [
            'controller_name' => 'UtilisateurController',
            'form'=>$form->createView()
        ]);
    }/**
     * @Route("/Activation/{email}", name="Activation")
     */
    public function ActivateAccount(Request $request,$email,FreelancerRepository $freelancerRepository,SocieteRepository $societeRepository): Response
    {
        $freelancer= $freelancerRepository->findOneBy(['email'=>$email]);
        $societe= $societeRepository->findOneBy(['email'=>$email]);
        if($freelancer!=null){
            $freelancer->setEtat(0);
            $em=$this->getDoctrine()->getManager();
            // actually executes the queries
            $em->flush();
        }elseif($societe!=null){
            $societe->setEtat(0);
            $em=$this->getDoctrine()->getManager();
            // actually executes the queries
            $em->flush();
        }


        return $this->redirectToRoute('SignIn');
    }

    /**
     * @param Request $request
     * @Route("/ProfileFreelancer", name="ProfileFreelancer")
     */
    public function ProfileFreelancer(FreelancerRepository $repository,Request $request,ReviewsRepository $reviewsRepository,ReviewsTextualRepository $reviewsTextualRepository): Response
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
            if($review != null){
                foreach ($review as $i){
                    $j++;
                    $value+= $i->getNumberReviews();
                }
                $session->set('NumbReviews',round($value/$j));
            }else{
                $session->set('NumbReviews',0);
            }


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
            $reviewsTextual=$reviewsTextualRepository->findBy(array('idTaker'=>$session->get('id'),'typeTaker'=>'freelancer'));
            $em = $this->getDoctrine()->getRepository(Reclamation::class);
            $list = $em->findBy(array('email_utilisateur'=>$session->get('email')));
            return $this->render('utilisateur/FreelancerProfile.html.twig', [
            'controller_name' => 'UtilisateurController',
            'form'=>$form->createView(),
                'reviews'=>$reviewsTextual,
                'list'=>$list,
            ]);}
    }

    /**
     * @param Request $request
     * @Route("/ProfileSociete", name="ProfileSociete")
     */
    public function ProfileSociete(SocieteRepository $repository,Request $request,ReviewsRepository $reviewsRepository,ReviewsTextualRepository $reviewsTextualRepository): Response
    {
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }
        else{
            $societe=$repository->find($this->get('session')->get('id'));
            $form=$this->createForm(SocieteProfileType::class,$societe);
            $form->handleRequest($request);

            $review=$reviewsRepository->findBy(['idTaker'=>$session->get('id')]);
            $value=0;
            $j=0;
            if($review != null){
                foreach ($review as $i){
                    $j++;
                    $value+= $i->getNumberReviews();
                }
                $session->set('NumbReviews',round($value/$j));
            }else{
                $session->set('NumbReviews',0);
            }

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
            $reviewsTextual=$reviewsTextualRepository->findBy(array('idTaker'=>$session->get('id'),'typeTaker'=>'societe'));

            $em = $this->getDoctrine()->getRepository(Reclamation::class);
            $list = $em->findBy(array('email_utilisateur'=>$session->get('email')));
            return $this->render('utilisateur/SocieteProfile.html.twig', [
                'controller_name' => 'UtilisateurController',
                'form'=>$form->createView(),
                'reviews'=>$reviewsTextual,
                'list'=>$list
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

    /**
     * @param Request $request
     * @Route("/ProfileFF/{id}", name="ViewFreelancerProfileF")
     */
    public function ViewFProfile(ReviewsRepository $reviewsRepository,$id,FreelancerRepository $freelancerRepository, SocieteRepository $societeRepository,Request $request,ReviewsTextualRepository $reviewsTextualRepository): Response
    {

        $session=$request->getSession();
        $freelancer=$freelancerRepository->find($id);
        $review=$reviewsRepository->findBy(['idTaker'=>$id]);
        $value=0;
        $j=0;
        $numbrev=0;
        if($review != null){
            foreach ($review as $i){
                $j++;
                $value+= $i->getNumberReviews();
            }
            $numbrev=round($value/$j);
        }else{
            $numbrev=0;
        }

        $reviewT=new ReviewsTextual();
        $form=$this->createForm(ReviewsTextType::class,$reviewT);
        $form->handleRequest($request);
        $societe = $societeRepository->find($session->get('id'));
        if($form->isSubmitted()){
            $reviewT->setSociete($societe);
            $reviewT->setIdTaker($id);
            $reviewT->setTypeTaker('freelancer');
            $em = $this->getDoctrine()->getManager();
            $em->persist($reviewT);
            $em->flush();
            return $this->redirectToRoute('ViewFreelancerProfileF', array('id' => $id));
        }
        $freelancer->setViewsNb($freelancer->getViewsNb()+1);
        $this->get('session')->set('viewsNb',$freelancer->getViewsNb());
        $em=$this->getDoctrine()->getManager();
        // actually executes the queries
        $em->flush();
        $reviewsTextual=$reviewsTextualRepository->findBy(array('idTaker'=>$id,'typeTaker'=>'freelancer'));

        return $this->render('utilisateur/ViewUserProfile.html.twig', [
            'controller_name' => 'UtilisateurBackController',
            'profile'=>$freelancer,
            'type'=>'Freelancer',
            'form'=>$form->createView(),
            'reviews'=>$reviewsTextual,
            'nbpersone'=>$j,
            'numbrev'=>$numbrev
        ]);
    }

    /**
     * @param Request $request
     * @Route("/ProfileSF/{id}", name="ViewSocieteProfileF")
     */
    public function ViewSProfile(ReviewsRepository $reviewsRepository,$id,FreelancerRepository $freelancerRepository, SocieteRepository $societeRepository,Request $request,ReviewsTextualRepository $reviewsTextualRepository): Response
    {
        $session = $request->getSession();
        $review=$reviewsRepository->findBy(['idTaker'=>$id]);
        $value=0;
        $j=0;
        $numbrev=0;
        if($review != null){
            foreach ($review as $i){
                $j++;
                $value+= $i->getNumberReviews();
            }
            $numbrev=round($value/$j);
        }else{
            $numbrev=0;
        }
        $reviewT=new ReviewsTextual();
        $form=$this->createForm(ReviewsTextType::class,$reviewT);
        $form->handleRequest($request);
        $freelancer = $freelancerRepository->find($session->get('id'));
        if($form->isSubmitted()){
            $reviewT->setFreelancer($freelancer);
            $reviewT->setIdTaker($id);
            $reviewT->setTypeTaker('societe');
            $em = $this->getDoctrine()->getManager();
            $em->persist($reviewT);
            $em->flush();
            return $this->redirectToRoute('ViewSocieteProfileF', array('id' => $id));
        }

        $societe=$societeRepository->find($id);
        $societe->setViewsNb($societe->getViewsNb()+1);
        $em=$this->getDoctrine()->getManager();
        // actually executes the queries
        $em->flush();

        $reviewsTextual=$reviewsTextualRepository->findBy(array('idTaker'=>$id,'typeTaker'=>'societe'));


        return $this->render('utilisateur/ViewUserProfile.html.twig', [
            'controller_name' => 'UtilisateurBackController',
            'profile'=>$societe,
            'type'=>'Societe',
            'form'=>$form->createView(),
            'reviews'=>$reviewsTextual,
            'nbpersone'=>$j,
            'numbrev'=>$numbrev
        ]);
    }

    /**
     * @Route("/ratingFreelancer/{numb}?{idTaker}?{id}", name="ratingFreelancer")
     */
    public function Rating(ReviewsRepository $repository,$numb,$idTaker,$id,Request $request)
    {
        $session = $request->getSession();
        $review=$repository->findOneBy(['idTaker'=>$idTaker,'idGiver'=>$session->get('id')]);
        if($review!=null){
            $review->setNumberReviews($numb);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
        }else{
            $reviewInstance=new Reviews();
            $reviewInstance->setIdGiver($session->get('id'));
            $reviewInstance->setIdTaker($idTaker);
            $reviewInstance->setNumberReviews($numb);
            $em=$this->getDoctrine()->getManager();
            $em->persist($reviewInstance);
            $em->flush();
        }
        return $this->redirectToRoute('ViewFreelancerProfileF', array('id'=>$id));
    }

    /**
     * @Route("/ratingSocite/{numb}?{idTaker}?{id}", name="ratingSocite")
     */
    public function RatingSos(ReviewsRepository $repository,$numb,$idTaker,$id,Request $request)
    {
        $session = $request->getSession();
        $review=$repository->findOneBy(['idTaker'=>$idTaker,'idGiver'=>$session->get('id')]);
        if($review!=null){
            $review->setNumberReviews($numb);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
        }else{
            $reviewInstance=new Reviews();
            $reviewInstance->setIdGiver($session->get('id'));
            $reviewInstance->setIdTaker($idTaker);
            $reviewInstance->setNumberReviews($numb);
            $em=$this->getDoctrine()->getManager();
            $em->persist($reviewInstance);
            $em->flush();
        }
        return $this->redirectToRoute('ViewSocieteProfileF', array('id'=>$id));
    }

}
