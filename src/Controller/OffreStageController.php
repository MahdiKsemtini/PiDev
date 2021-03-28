<?php

namespace App\Controller;

use App\Entity\OffreStage;
use App\Form\StageType;
use App\Repository\AdminEmploiRepository;
use App\Repository\AdminRepository;
use App\Repository\OffreStageRepository;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreStageController extends AbstractController
{
    /**
     * @Route("/offre/stage", name="offre_stage")
     */
    public function index(): Response
    {
        return $this->render('offre_stage/index.html.twig', [
            'controller_name' => 'OffreStageController',
        ]);
    }

    /**
     * @Route("/addStage", name="addStage")
     * @param Request $request
     * @param OffreStageRepository $stageRepository
     * @param AdminRepository $adminRepository
     * @param SocieteRepository $societeRepository
     * @param AdminEmploiController $adminEmploiController
     * @param AdminEmploiRepository $adminEmploiRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addStage(Request $request,OffreStageRepository $stageRepository,AdminRepository $adminRepository,SocieteRepository $societeRepository,AdminEmploiController $adminEmploiController,AdminEmploiRepository $adminEmploiRepository){
        $stage = new OffreStage();
        $form = $this->createForm(StageType::class, $stage);
        // $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            //  dd();
            $em = $this->getDoctrine()->getManager();
            $newDate= new \DateTime('now');
            $stage->setDateCreation($newDate);
            $stage->setEtat(0);
            $stage->setSociete($societeRepository->find(1));



            $NonApprouve = $stageRepository->countOffreStageNonApprouve();

            $admins = $adminRepository->findBy(array('type'=>'Admin des emplois'));

            foreach ($NonApprouve as $count) {
                foreach ($admins as $admin) {
                    $adminRepository->find($admin->getId())->setNonapprouve(((integer)$count['count']) + 1);

                }
            }



            $em->persist($stage);
            $em->flush();


            $societeemail = $societeRepository->find($stage->getSociete());


            $adminEmploiController->OffreStageToAdmin($adminRepository,$stage->getId(),$societeemail->getEmail(),$stageRepository,$adminEmploiRepository);


            return $this->redirectToRoute('showStage');
        }
        return $this->render('offre_stage/CreateOffreStage.html.twig', [
            "f" => $form->createView(),
        ]);
    }
}
