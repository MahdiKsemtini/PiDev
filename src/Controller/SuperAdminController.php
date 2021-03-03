<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuperAdminController extends AbstractController
{
    /**
     * @Route("/super/admin", name="super_admin")
     */
    public function index(): Response
    {
        $em=$this->getDoctrine()->getRepository(Admin::class);
        $list= $em->findAll();
        return $this->render('super_admin/index.html.twig', [ 'list'=>$list ]);
    }

   /**
     * @Route("/super/admin/CreateAdmin", name="CreateAdmin")
     */
    public function ajouterAdmin(Request $request)
    {
        $admin = new Admin();
        $form = $this->createForm(AdminFormType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute("super_admin");
        }
        return $this->render('super_admin/CreateAdmin.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @param $id
     * @Route ("/super/admin/ViewAdminProfile/{id}" , name="ViewAdminProfile")
     */
    public function AfficheAdmin($id)
    {
        $em=$this->getDoctrine()->getManager();
        $admin =$em->getRepository(Admin::class)->find($id);
        return $this->render('super_admin/ViewAdminProfile.html.twig',['admin' =>$admin]);

    }




}
