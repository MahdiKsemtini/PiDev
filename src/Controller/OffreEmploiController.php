<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Form\EmploiType;
use App\Repository\AdminRepository;
use App\Repository\OffreEmploiRepository;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreEmploiController extends AbstractController
{
    /**
     * @Route("/addEmploi", name="addEmploi")
     * @param Request $request
     * @param OffreEmploiRepository $emploiRepository
     * @param AdminRepository $adminRepository
     * @param AdminEmploiController $adminEmploiController
     * @param SocieteRepository $societeRepository
     * @return RedirectResponse|Response
     */
    public function addEmploi(Request $request, OffreEmploiRepository $emploiRepository,AdminRepository $adminRepository,AdminEmploiController $adminEmploiController,SocieteRepository $societeRepository){

        $emploi = new OffreEmploi();
        //   $emploi->setFichier(($baseurl . "/uploads/cantine/" . $newFilename));
        $form = $this->createForm(EmploiType::class, $emploi);
        // $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            //  dd();
            $em = $this->getDoctrine()->getManager();
            $newDate= new \DateTime('now');
            $emploi->setDateCreation($newDate);
            $emploi->setEtat(0);
            $societe = $societeRepository->find(1);
            $emploi->setSociete($societe);


            $NonApprouve = $emploiRepository->countOffreEmploiNonApprouve();

            $admins = $adminRepository->findBy(array('type'=>'Admin des emplois'));

            foreach ($NonApprouve as $count) {
                foreach ($admins as $admin) {
                    $adminRepository->find($admin->getId())->setNonapprouve(((integer)$count['count']) + 1);

                }
            }


                $em->persist($emploi);
            $em->flush();


            $societeemail = $societeRepository->find($emploi->getSociete());
            $adminEmploiController->OffreEmploiToAdmin($adminRepository,$emploi->getId(),$societeemail->getEmail(),$emploiRepository);

            return $this->redirectToRoute('showEmploi');
        }
        return $this->render('offre_emploi/CreateOffreEmploi.html.twig', [
            "f" => $form->createView(),
        ]);



    }
}
