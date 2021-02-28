<?php

namespace App\Controller;

use App\Entity\Publications;
use App\Form\PublicationsType;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicationsController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index(): Response
    {
        return $this->render('publications/forum.html.twig', [
            'controller_name' => 'PublicationsController',
        ]);
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

}
