<?php

namespace App\Controller;

use App\Repository\FreelancerRepository;
use App\Repository\OffreEmploiRepository;
use App\Repository\OffreStageRepository;
use App\Repository\ReclamationRepository;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    /**
     * @Route("/stat", name="stat")
     * @param ReclamationRepository $reclamationRepository
     * @param FreelancerRepository $freelancerRepository
     * @param SocieteRepository $societeRepository
     * @param OffreStageRepository $offreStageRepository
     * @param OffreEmploiRepository $offreEmploiRepository
     * @return Response
     */
    public function index(ReclamationRepository $reclamationRepository , FreelancerRepository $freelancerRepository,SocieteRepository $societeRepository, OffreStageRepository $offreStageRepository,OffreEmploiRepository $offreEmploiRepository): Response
    {

        //Statestique sur nombre de reclamation par mois

        //Count des reclamations
        $nbReclamation = $reclamationRepository->countReclamtion();
        $countRec=0;
        foreach ($nbReclamation as $ArrayRec){
            $countRec = $ArrayRec['count'];
        }

        //Count des reclamations par mois
        $reclamationParMois = $reclamationRepository->reclamationParMois();

        $Recjanvier =0;
        $Recfevrier = 0;
        $Recmars = 0;
        $Recavril = 0;
        $Recmai = 0;
        $Recjuin = 0;
        $Recjuillet = 0;
        $Recaout = 0;
        $Recseptemebre = 0;
        $Recoctobre = 0;
        $Recnovembre = 0;
        $Recdecembre = 0;
        foreach ($reclamationParMois as $count)
        {
            if ($count['mois'] == '1')
            {
                $Recjanvier = $count['count'];
            }
            if ($count['mois'] == '2')
            {
                $Recfevrier = $count['count'];
            }
            if ($count['mois'] == '3')
            {
                $Recmars = $count['count'];
            }
            if ($count['mois'] == '4')
            {
                $Recavril = $count['count'];
            }
            if ($count['mois'] == '5')
            {
                $Recmai = $count['count'];
            }
            if ($count['mois'] == '6')
            {
                $Recjuin = $count['count'];
            }
            if ($count['mois'] == '7')
            {
                $Recjuillet= $count['count'];
            }
            if ($count['mois'] == '8')
            {
                $Recaout = $count['count'];
            }
            if ($count['mois'] == '9')
            {
                $Recseptemebre = $count['count'];
            }
            if ($count['mois'] == '10')
            {
                $Recoctobre = $count['count'];
            }
            if ($count['mois'] == '11')
            {
                $Recnovembre = $count['count'];
            }
            if ($count['mois'] == '12')
            {
                $Recdecembre = $count['count'];
            }
            //dd($janvier,$fevrier,$mars, $avril,$mai,$juin,$juillet,$aout,$septemebre,$octobre,$novembre,$decembre);
        }

        //Data de reclamation pour javaScript
        $ReclamationScript = "data: [".$Recjanvier.",".$Recfevrier.",".$Recmars.",".$Recavril.",".$Recmai.",".$Recjuin.",".$Recjuillet.",
        ".$Recaout.",".$Recseptemebre.",".$Recoctobre.",".$Recnovembre.",".$Recdecembre."]";

        //Statestique sur nombre d'utilisateur par mois

        //count des freelancers
        $nbFreelancer = $freelancerRepository->countfreelancer();
        $countfree=0;
        foreach ($nbFreelancer as $ArrayFree){
            $countfree = $ArrayFree['count'];
        }

        //count des societes
        $nbSociete = $societeRepository->countsociete();
        $countsos =0;

        foreach ($nbSociete as $ArraySos){
            $countsos = $ArraySos['count'];
        }

        //Somme des deux count
        $countUsers = $countsos+$countfree;

        //Count des Freelancer par mois

        $freelancerParMois = $freelancerRepository->freelancerParMois();

        $Freejanvier =0;
        $Freefevrier = 0;
        $Freemars = 0;
        $Freeavril = 0;
        $Freemai = 0;
        $Freejuin = 0;
        $Freejuillet = 0;
        $Freeaout = 0;
        $Freeseptemebre = 0;
        $Freeoctobre = 0;
        $Freenovembre = 0;
        $Freedecembre = 0;
        foreach ($freelancerParMois as $countfree)
        {
            if ($countfree['mois'] == '1')
            {
                $Freejanvier = $countfree['count'];
            }
            if ($countfree['mois'] == '2')
            {
                $Freefevrier = $countfree['count'];
            }
            if ($countfree['mois'] == '3')
            {
                $Freemars = $countfree['count'];
            }
            if ($countfree['mois'] == '4')
            {
                $Freeavril = $countfree['count'];
            }
            if ($countfree['mois'] == '5')
            {
                $Freemai = $countfree['count'];
            }
            if ($countfree['mois'] == '6')
            {
                $Freejuin = $countfree['count'];
            }
            if ($countfree['mois'] == '7')
            {
                $Freejuillet= $countfree['count'];
            }
            if ($countfree['mois'] == '8')
            {
                $Freeaout = $countfree['count'];
            }
            if ($countfree['mois'] == '9')
            {
                $Freeseptemebre = $countfree['count'];
            }
            if ($countfree['mois'] == '10')
            {
                $Freeoctobre = $countfree['count'];
            }
            if ($countfree['mois'] == '11')
            {
                $Freenovembre = $countfree['count'];
            }
            if ($countfree['mois'] == '12')
            {
                $Freedecembre = $countfree['count'];
            }
            //dd($janvier,$fevrier,$mars, $avril,$mai,$juin,$juillet,$aout,$septemebre,$octobre,$novembre,$decembre);
        }

        //Count des societe par mois


        $societeParMois = $societeRepository->societeParMois();

        $Sosjanvier =0;
        $Sosfevrier = 0;
        $Sosmars = 0;
        $Sosavril = 0;
        $Sosmai = 0;
        $Sosjuin = 0;
        $Sosjuillet = 0;
        $Sosaout = 0;
        $Sosseptemebre = 0;
        $Sosoctobre = 0;
        $Sosnovembre = 0;
        $Sosdecembre = 0;
        foreach ($societeParMois as $countsos)
        {
            if ($countsos['mois'] == '1')
            {
                $Sosjanvier = $countsos['count'];
            }
            if ($countsos['mois'] == '2')
            {
                $Sosfevrier = $countsos['count'];
            }
            if ($countsos['mois'] == '3')
            {
                $Sosmars = $countsos['count'];
            }
            if ($countsos['mois'] == '4')
            {
                $Sosavril = $countsos['count'];
            }
            if ($countsos['mois'] == '5')
            {
                $Sosmai = $countsos['count'];
            }
            if ($countsos['mois'] == '6')
            {
                $Sosjuin = $countsos['count'];
            }
            if ($countsos['mois'] == '7')
            {
                $Sosjuillet= $countsos['count'];
            }
            if ($countsos['mois'] == '8')
            {
                $Sosaout = $countsos['count'];
            }
            if ($countsos['mois'] == '9')
            {
                $Sosseptemebre = $countsos['count'];
            }
            if ($countsos['mois'] == '10')
            {
                $Sosoctobre = $countsos['count'];
            }
            if ($countsos['mois'] == '11')
            {
                $Sosnovembre = $countsos['count'];
            }
            if ($countsos['mois'] == '12')
            {
                $Sosdecembre = $countsos['count'];
            }
            //dd($janvier,$fevrier,$mars, $avril,$mai,$juin,$juillet,$aout,$septemebre,$octobre,$novembre,$decembre);
        }

        //Count user par mois

        $Userjanvier = $Freejanvier+$Sosjanvier;
        $Userfevrier = $Freefevrier+$Sosfevrier;
        $Usermars = $Freemars+$Sosmars;
        $Useravril = $Freeavril+$Sosavril;
        $Usermai = $Freemai+$Sosmai;
        $Userjuin = $Freejuin+$Sosjuin;
        $Userjuillet = $Freejuillet+$Sosjuillet;
        $Useraout = $Freeaout+$Sosaout;
        $Userseptemebre = $Freeseptemebre +$Sosseptemebre ;
        $Useroctobre = $Freeoctobre+$Sosoctobre;
        $Usernovembre = $Freenovembre+$Sosnovembre;
        $Userdecembre = $Freedecembre+$Sosdecembre;


        //Data des utilisateurs pour javaScript
        $UserScript = "data: [".$Userjanvier.",".$Userfevrier.",".$Usermars.",".$Useravril.",".$Usermai.",".$Userjuin.",".$Userjuillet.",
        ".$Useraout.",".$Userseptemebre.",".$Useroctobre.",".$Usernovembre.",".$Userdecembre."]";



        //statestique pour nombre des offre d'emploi et Stage par mois

        //count des offre d'emploi
        $nbOffreEmploi = $offreEmploiRepository->countOffreEmploi();
        $countOffreEmploi=0;
        foreach ($nbOffreEmploi as $Arrayoffreemploi){
            $countOffreEmploi = $Arrayoffreemploi['count'];
        }

        //count des offre de stage
        $nbOffreStage = $offreStageRepository->countOffreStage();
        $countOffreStage=0;
        foreach ($nbOffreStage as $Arrayoffrestage){
            $countOffreStage = $Arrayoffrestage['count'];
        }

        //Somme des deux count
        $countOffre = $countOffreEmploi+$countOffreStage;

        //Count des offre d'emploi par mois

        $offreemploiParMois = $offreEmploiRepository->OffreEmploiParMois();

        $OffreEmploijanvier =0;
        $OffreEmploifevrier = 0;
        $OffreEmploimars = 0;
        $OffreEmploiavril = 0;
        $OffreEmploimai = 0;
        $OffreEmploijuin = 0;
        $OffreEmploijuillet = 0;
        $OffreEmploiaout = 0;
        $OffreEmploiseptemebre = 0;
        $OffreEmploioctobre = 0;
        $OffreEmploinovembre = 0;
        $OffreEmploidecembre = 0;
        foreach ($offreemploiParMois as $countoffreEmploi)
        {
            if ($countoffreEmploi['mois'] == '1')
            {
                $OffreEmploijanvier = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '2')
            {
                $OffreEmploifevrier = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '3')
            {
                $OffreEmploimars = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '4')
            {
                $OffreEmploiavril = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '5')
            {
                $OffreEmploimai = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '6')
            {
                $OffreEmploijuin = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '7')
            {
                $OffreEmploijuillet= $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '8')
            {
                $OffreEmploiaout = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '9')
            {
                $OffreEmploiseptemebre = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '10')
            {
                $OffreEmploioctobre = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '11')
            {
                $OffreEmploinovembre = $countoffreEmploi['count'];
            }
            if ($countoffreEmploi['mois'] == '12')
            {
                $OffreEmploidecembre = $countoffreEmploi['count'];
            }
        }

        //Data des offre d'emploi pour javaScript
        $OffreEmploiScript = "data: [".$OffreEmploijanvier.",".$OffreEmploifevrier.",".$OffreEmploimars.",".$OffreEmploiavril.",".$OffreEmploimai.",".$OffreEmploijuin.",".$OffreEmploijuillet.",
        ".$OffreEmploiaout.",".$OffreEmploiseptemebre.",".$OffreEmploioctobre.",".$OffreEmploinovembre.",".$OffreEmploidecembre."]";



        //statestique pour nombre des offre de stage par mois




        //Count des offre d'emploi par mois

        $offrestageParMois = $offreStageRepository->OffreStageParMois();

        $OffreStagejanvier =0;
        $OffreStagefevrier = 0;
        $OffreStagemars = 0;
        $OffreStageavril = 0;
        $OffreStagemai = 0;
        $OffreStagejuin = 0;
        $OffreStagejuillet = 0;
        $OffreStageaout = 0;
        $OffreStageseptemebre = 0;
        $OffreStageoctobre = 0;
        $OffreStagenovembre = 0;
        $OffreStagedecembre = 0;
        foreach ($offrestageParMois as $countoffreStage)
        {
            if ($countoffreStage['mois'] == '1')
            {
                $OffreStagejanvier = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '2')
            {
                $OffreStagefevrier = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '3')
            {
                $OffreStagemars = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '4')
            {
                $OffreStageavril = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '5')
            {
                $OffreStagemai = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '6')
            {
                $OffreStagejuin = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '7')
            {
                $OffreStagejuillet= $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '8')
            {
                $OffreStageaout = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '9')
            {
                $OffreStageseptemebre = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '10')
            {
                $OffreStageoctobre = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '11')
            {
                $OffreStagenovembre = $countoffreStage['count'];
            }
            if ($countoffreStage['mois'] == '12')
            {
                $OffreStagedecembre = $countoffreStage['count'];
            }
        }

        //Data des offre d'emploi pour javaScript
        $OffreStageScript = "data: [".$OffreStagejanvier.",".$OffreStagefevrier.",".$OffreStagemars.",".$OffreStageavril.",".$OffreStagemai.",".$OffreStagejuin.",".$OffreStagejuillet.",
        ".$OffreStageaout.",".$OffreStageseptemebre.",".$OffreStageoctobre.",".$OffreStagenovembre.",".$OffreStagedecembre."]";



        //statestique pour nombre des demande d'emploi et Stage par mois

        //count des demande d'emploi
        $nbDemandeEmploi = $demandeEmploiRepository->countDemandeEmploi();
        $countDemandeEmploi=0;
        foreach ($nbDemandeEmploi as $Arraydemandeemploi){
            $countDemandeEmploi = $Arraydemandeemploi['count'];
        }

        //count des offre de stage
        $nbDemandeStage = $demandeStageRepository->countdemandeStage();
        $countDemandeStage=0;
        foreach ($nbDemandeStage as $Arraydemandestage){
            $countDemandeStage = $Arraydemandestage['count'];
        }

        //Somme des deux count
        $countDemande = $countDemandeEmploi+$countDemandeStage;

        //Count des offre d'emploi par mois

        $demandeemploiParMois = $demandeEmploiRepository->DemandeEmploiParMois();

        $DemandeEmploijanvier =0;
        $DemandeEmploifevrier = 0;
        $DemandeEmploimars = 0;
        $DemandeEmploiavril = 0;
        $DemandeEmploimai = 0;
        $DemandeEmploijuin = 0;
        $DemandeEmploijuillet = 0;
        $DemandeEmploiaout = 0;
        $DemandeEmploiseptemebre = 0;
        $DemandeEmploioctobre = 0;
        $DemandeEmploinovembre = 0;
        $DemandeEmploidecembre = 0;
        foreach ($demandeemploiParMois as $countdemandeEmploi)
        {
            if ($countdemandeEmploi['mois'] == '1')
            {
                $DemandeEmploijanvier = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '2')
            {
                $DemandeEmploifevrier = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '3')
            {
                $DemandeEmploimars = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '4')
            {
                $DemandeEmploiavril = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '5')
            {
                $DemandeEmploimai = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '6')
            {
                $DemandeEmploijuin = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '7')
            {
                $DemandeEmploijuillet= $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '8')
            {
                $DemandeEmploiaout = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '9')
            {
                $DemandeEmploiseptemebre = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '10')
            {
                $DemandeEmploioctobre = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '11')
            {
                $DemandeEmploinovembre = $countdemandeEmploi['count'];
            }
            if ($countdemandeEmploi['mois'] == '12')
            {
                $DemandeEmploidecembre = $countdemandeEmploi['count'];
            }
        }

        //Data des offre d'emploi pour javaScript
        $DemandeEmploiScript = "data: [".$DemandeEmploijanvier.",".$DemandeEmploifevrier.",".$DemandeEmploimars.",".$DemandeEmploiavril.",".$DemandeEmploimai.",".$DemandeEmploijuin.",".$DemandeEmploijuillet.",
        ".$DemandeEmploiaout.",".$DemandeEmploiseptemebre.",".$DemandeEmploioctobre.",".$DemandeEmploinovembre.",".$DemandeEmploidecembre."]";



        //statestique pour nombre des demande de stage par mois




        //Count des demande d'emploi par mois

        $demandestageParMois = $demandeStageRepository->DemandeStageParMois();

        $DemandeStagejanvier =0;
        $DemandeStagefevrier = 0;
        $DemandeStagemars = 0;
        $DemandeStageavril = 0;
        $DemandeStagemai = 0;
        $DemandeStagejuin = 0;
        $DemandeStagejuillet = 0;
        $DemandeStageaout = 0;
        $DemandeStageseptemebre = 0;
        $DemandeStageoctobre = 0;
        $DemandeStagenovembre = 0;
        $DemandeStagedecembre = 0;
        foreach ($demandestageParMois as $countdemandeStage)
        {
            if ($countdemandeStage['mois'] == '1')
            {
                $DemandeStagejanvier = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '2')
            {
                $DemandeStagefevrier = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '3')
            {
                $DemandeStagemars = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '4')
            {
                $DemandeStageavril = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '5')
            {
                $DemandeStagemai = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '6')
            {
                $DemandeStagejuin = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '7')
            {
                $DemandeStagejuillet= $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '8')
            {
                $DemandeStageaout = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '9')
            {
                $DemandeStageseptemebre = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '10')
            {
                $DemandeStageoctobre = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '11')
            {
                $DemandeStagenovembre = $countdemandeStage['count'];
            }
            if ($countdemandeStage['mois'] == '12')
            {
                $countdemandeStage = $countdemandeStage['count'];
            }
        }

        //Data des offre d'emploi pour javaScript
        $DemandeStageScript = "data: [".$DemandeStagejanvier.",".$DemandeStagefevrier.",".$DemandeStagemars.",".$DemandeStageavril.",".$DemandeStagemai.",".$DemandeStagejuin.",".$DemandeStagejuillet.",
        ".$DemandeStageaout.",".$DemandeStageseptemebre.",".$DemandeStageoctobre.",".$DemandeStagenovembre.",".$DemandeStagedecembre."]";



        return $this->render('stat/index.html.twig', [

            'nbReclamation'=>$countRec,
            'nbUtilisateur'=>$countUsers,
            'nbOffreEmploi' =>$countOffreEmploi,
            'nbOffreStage'=>$countOffreStage,
            'nbOffre'=>$countOffre,
            'nbDemandeEmploi'=>$countDemandeEmploi,
            'nbDemandeStage'=>$countDemandeStage,
            'nbDemande'=>$countDemande,

            'ReclamationData'=>$ReclamationScript,
            'UtilisateurData'=>$UserScript,
            'OffreEmploiData' =>$OffreEmploiScript,
            'OffreStageData'=>$OffreStageScript,
            'DemandeEmploiData' =>$DemandeEmploiScript,
            'DemandeStageData'=>$DemandeStageScript



            /*'Recjan'=>json_encode($Recjanvier),
            'Recfev'=>json_encode($Recfevrier),
            'Recmars'=>json_encode($Recmars),
            'Recavr'=>json_encode($Recavril),
            'Recmai'=>json_encode($Recmai),
            'Recjuin'=>json_encode($Recjuin),
            'Recjuil'=>json_encode($Recjuillet),
            'Recaout'=>json_encode($Recaout),
            'Recsep'=>json_encode($Recseptemebre),
            'Recoct'=>json_encode($Recoctobre),
            'Recnov'=>json_encode($Recnovembre),
            'Recdec'=>json_encode($Recdecembre)*/

        ]);
    }


    /*public function statestique(ReclamationRepository $reclamationRepository)
    {
        $reclamationParMois = $reclamationRepository->reclamationParMois();

        $janvier =0;
        $fevrier = 0;
        $mars = 0;
        $avril = 0;
        $mai = 0;
        $juin = 0;
        $juillet = 0;
        $aout = 0;
        $septemebre = 0;
        $octobre = 0;
        $novembre = 0;
        $decembre = 0;
        foreach ($reclamationParMois as $count)
        {
            if ($count['mois'] == '1')
            {
                $janvier = $count['count'];
            }
            if ($count['mois'] == '2')
            {
                $fevrier = $count['count'];
            }
            if ($count['mois'] == '3')
            {
                $mars = $count['count'];
            }
            if ($count['mois'] == '4')
            {
                $avril = $count['count'];
            }
            if ($count['mois'] == '5')
            {
                $mai = $count['count'];
            }
            if ($count['mois'] == '6')
            {
                $juin = $count['count'];
            }
            if ($count['mois'] == '7')
            {
                $juillet= $count['count'];
            }
            if ($count['mois'] == '8')
            {
                $aout = $count['count'];
            }
            if ($count['mois'] == '9')
            {
                $septemebre = $count['count'];
            }
            if ($count['mois'] == '10')
            {
                $octobre = $count['count'];
            }
            if ($count['mois'] == '11')
            {
                $novembre = $count['count'];
            }
            if ($count['mois'] == '12')
            {
                $decembre = $count['count'];
            }
            dd($janvier,$fevrier,$mars, $avril,$mai,$juin,$juillet,$aout,$septemebre,$octobre,$novembre,$decembre);
        }

        return $this->render('Statestique/stat.html.twig' , [

            'jan'=>json_encode((integer)$janvier),
            'fev'=>json_encode((integer)$fevrier),
            'mars'=>json_encode((integer)$mars),
            'avr'=>json_encode((integer)$avril),
            'mai'=>json_encode((integer)$mai),
            'juin'=>json_encode((integer)$juin),
            'juil'=>json_encode((integer)$juillet),
            'aout'=>json_encode((integer)$aout),
            'sep'=>json_encode((integer)$septemebre),
            'oct'=>json_encode((integer)$octobre),
            'nov'=>json_encode((integer)$novembre),
            'dec'=>json_encode((integer)$decembre)
        ]);
    }*/
}
