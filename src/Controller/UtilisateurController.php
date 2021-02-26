<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/SignIn", name="SignIn")
     */
    public function SignIn(): Response
    {
        return $this->render('utilisateur/Login.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("/SignUp", name="SignUp")
     */
    public function SignUp(): Response
    {
        return $this->render('utilisateur/SignUp.html.twig', [
            'controller_name' => 'UtilisateurController',
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
     * @Route("/Create", name="Create")
     */
    public function Create(): Response
    {
        return $this->render('utilisateur/create.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }


}
