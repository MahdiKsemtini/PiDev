<?php

namespace App\Controller;

use App\Entity\AdminReclamtion;
use App\Repository\AdminReclamtionRepository;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReclamationController extends AbstractController
{
    /**
     * @Route("/admin/reclamation", name="admin_reclamation")
     */

public function ReclamationToAdmin(AdminRepository $adminRepository , $IdReclamtion)
{
    $list = $adminRepository->findBy(array('type'=>'Admin des reclamations', 'etat'=>1));

    $em = $this->getDoctrine()->getManager();
    foreach ($list as $l)
    {
        $id =$l->getId();
        $adminReclamation = new AdminReclamation();
        $adminReclamation->setIdAR($id);
        $adminReclamation->setIdReclamation($IdReclamtion);
        $em->persist($adminReclamation);
        $em->flush();
    }
}
}
