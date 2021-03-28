<?php

namespace App\Notifications;

// On importe les classes nécessaires à l'envoi d'e-mail et à twig

use App\Repository\OffreEmploiRepository;
use App\Repository\OffreStageRepository;
use App\Repository\ReclamationRepository;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class CreationCompteNotification
{
    /**
     * Propriété contenant le module d'envoi de mails
     *
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Propriété contenant l'environnement Twig
     *
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    /**
     * Méthode de notification (envoi de mail)
     *
     * @param $emailSource
     * @param $emailDestination
     * @param $Idreclamation
     * @param ReclamationRepository $reclamationRepository
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function notifyReclamation($emailSource,$emailDestination,$Idreclamation,ReclamationRepository $reclamationRepository)
    {

        $reclamation = $reclamationRepository->find($Idreclamation);
        // On construit le mail
        $message = (new Swift_Message('Mon blog - Nouvelle Reclamation'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination)
            // Corps du message
            ->setBody(
                $this->renderer->render(
                    'emails/ReclamationNotification.html.twig',['type'=>$reclamation->getType(),'message'=>$reclamation->getTexteReclamation()]
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }

    /**
     * Méthode de notification (envoi de mail)
     *
     * @param $emailSource
     * @param $emailDestination
     * @param $idOffreEmploi
     * @param OffreEmploiRepository $offreEmploiRepository
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function notifyOffreEmploi($emailSource,$emailDestination,$idOffreEmploi,OffreEmploiRepository $offreEmploiRepository)
    {


        // On construit le mail
        $message = (new Swift_Message('Mon blog - Nouvelle Offre d Emploi'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination)
            // Corps du message
            ->setBody(
                $this->renderer->render(
                    'emails/OffreEmploiNotification.html.twig'
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }
    public function notifyOffreStage($emailSource,$emailDestination,$idOffreStage,OffreStageRepository $offreStageRepository)
    {


        // On construit le mail
        $message = (new Swift_Message('Mon blog - Nouvelle Offre de stage'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination)
            // Corps du message
            ->setBody(
                $this->renderer->render(
                    'emails/OffreStageNotification.html.twig'
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }
}