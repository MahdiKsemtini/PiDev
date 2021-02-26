<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreEmploiController extends AbstractController
{
    /**
     * @Route("/emploi", name="emploi")
     */
    public function CreateOffreEmploi(): Response
    {
        return $this->render('offre_emploi/CreateOffreEmploi.html.twig', [
            'controller_name' => 'OffreEmploiController',
        ]);
    }
}
