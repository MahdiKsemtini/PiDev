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
        $random = random_int(1, 10);
        $em=$this->getDoctrine()->getRepository(Admin::class);
        $list= $em->findAll();
        return $this->render('super_admin/index.html.twig', [ 'list'=>$list , 'random'=>$random]);
    }

   /**
     * @Route("/super/admin/CreateAdmin", name="CreateAdmin")
     */
    public function ajouterAdmin(Request $request)
    {
        $admin = new Admin();
        $form = $this->createForm(AdminFormType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            if ($form->getData()->getEtat() == 'Active')
            {
                $admin->setEtat(1);
            }
            if ($form->getData()->getEtat() == 'Inactive')
            {
                $admin->setEtat(0);
            }
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

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/super/admin/UpdateAdmin/{id}" , name="UpdateAdmin")
     */
    public function UpdateAdmin(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $admin =$em->getRepository(Admin::class)->find($id);
        $form = $this->createForm(AdminFormType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            if ($form->getData()->getEtat() == 'Active')
            {
                $admin->setEtat(1);
            }
            if ($form->getData()->getEtat() == 'Inactive')
            {
                $admin->setEtat(0);
            }
            $em->flush();
            return $this->redirectToRoute("ViewAdminProfile", array('id'=>$id));
        }
        return $this->render('super_admin/UpdateAdmin.html.twig',['form' => $form->createView()]);

    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/super/admin/DeleteAdmin/{id}" , name="DeleteAdmin")
     */
    public function deleteAdmin($id)
    {
        $em = $this->getDoctrine()->getManager();
        $find = $em->getRepository(Admin::class)->find($id);
        $em->remove($find);
        $em->flush();
        return $this->redirectToRoute("super_admin");

    }

}
