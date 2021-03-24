<?php

namespace App\Controller;

use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    /**
     * @Route("/stat", name="stat")
     */
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        $nbReclamation = $reclamationRepository->countReclamtion();
        $countRec=0;
        foreach ($nbReclamation as $ArrayRec){
            $countRec = $ArrayRec['count'];
    }

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
            if ($count['mois'] == '01')
            {
                $Recjanvier = $count['count'];
            }
            if ($count['mois'] == '02')
            {
                $Recfevrier = $count['count'];
            }
            if ($count['mois'] == '03')
            {
                $Recmars = $count['count'];
            }
            if ($count['mois'] == '04')
            {
                $Recavril = $count['count'];
            }
            if ($count['mois'] == '05')
            {
                $Recmai = $count['count'];
            }
            if ($count['mois'] == '06')
            {
                $Recjuin = $count['count'];
            }
            if ($count['mois'] == '07')
            {
                $Recjuillet= $count['count'];
            }
            if ($count['mois'] == '08')
            {
                $Recaout = $count['count'];
            }
            if ($count['mois'] == '09')
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

        $ReclamationScript = "data: [".$Recjanvier.",".$Recfevrier.",".$Recmars.",".$Recavril.",".$Recmai.",".$Recjuin.",".$Recjuillet.",
        ".$Recaout.",".$Recseptemebre.",".$Recoctobre.",".$Recnovembre.",".$Recdecembre."]";
        return $this->render('stat/index.html.twig', [

            'nbReclamation'=>$countRec,

            'ReclamationData'=>$ReclamationScript



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
