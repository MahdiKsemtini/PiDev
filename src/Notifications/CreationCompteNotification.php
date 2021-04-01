<?php

namespace App\Notifications;

// On importe les classes nécessaires à l'envoi d'e-mail et à twig

use App\Repository\EventLoisirRepository;
use App\Repository\FormationRepository;
use App\Repository\OffreStageRepository;
use App\Repository\ReclamationRepository;
use Swift_Message;
use Twig\Environment;



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
     * @return void
     */
    public function notifyReclamation($emailSource,$emailDestination,$Idreclamation,ReclamationRepository $reclamationRepository)
    {

        $reclamation = $reclamationRepository->find($Idreclamation);
        // On construit le mail
        $message = (new Swift_Message('Mon blog - Nouvelle Reclamation'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination);
        $image= $message->embed(\Swift_Image::fromPath('images/Logo_complet.png'));
            // Corps du message
        $message ->setBody(
                $this->renderer->render(
                    'emails/ReclamationNotification.html.twig',['type'=>$reclamation->getType(),'message'=>$reclamation->getTexteReclamation(),'imageurl'=>$image]
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }
    /**
     * Méthode de notification (envoi de mail)
     *
     * @return void
     */
    public function notifyEmploi($emailSource,$emailDestination)
    {


        // On construit le mail
        $message = (new Swift_Message('Mon blog - Nouvelle Offre d Emploi'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination);
        $image= $message->embed(\Swift_Image::fromPath('images/Logo_complet.png'));
            // Corps du message
        $message->setBody(
                $this->renderer->render(
                    'emails/OffreEmploiNotification.html.twig',['imageurl'=>$image]
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }

    /**
     * Méthode de notification (envoi de mail)
     *
     * @return void
     */
    public function notifyUser($emailSource,$emailDestination,$nomprenom)
    {
        // On construit le mail

        $message = (new Swift_Message('Bienvenu '.$nomprenom))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination);
        $image= $message->embed(\Swift_Image::fromPath('images/Logo_complet.png'));
            // Corps du message
        $message->setBody(
                $this->renderer->render(
                    'emails/UserNotification.html.twig',['nomprenom'=>$nomprenom,'email'=>$emailDestination,'imageurl'=>$image]
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }

    /**
     * Méthode de notification (envoi de mail)
     *
     * @return void
     */
    public function notifyForgetPass($emailSource,$emailDestination)
    {
        // On construit le mail

        $message = (new Swift_Message('Mot de passe oublié'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination);
        $image= $message->embed(\Swift_Image::fromPath('images/Logo_complet.png'));
            // Corps du message
        $message->setBody(
                $this->renderer->render(
                    'emails/ForgetPasswordNotification.html.twig',['email'=>$emailDestination,'imageurl'=>$image]),
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
     * @return void
     */
    public function notifyOffreEmploi($emailSource, $emailDestination)
    {


        // On construit le mail
        $message = (new Swift_Message('Mon blog - Nouvelle Offre d Emploi'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination);
            // Corps du message
        $image= $message->embed(\Swift_Image::fromPath('images/Logo_complet.png'));
        $message->setBody(
                $this->renderer->render(
                    'emails/OffreEmploiNotification.html.twig',['imageurl'=>$image]
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
            ->setTo($emailDestination);
        $image= $message->embed(\Swift_Image::fromPath('images/Logo_complet.png'));

        // Corps du message
            $message->setBody(
                $this->renderer->render(
                    'emails/OffreStageNotification.html.twig',['imageurl'=>$image]
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }
    public function notifyEventLoisir($emailSource,$emailDestination,$idEvent,EventLoisirRepository $eventLoisirRepository)
    {


        // On construit le mail
        $message = (new Swift_Message('Mon blog - Nouvelle Evenement Détécté'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination);
        $image= $message->embed(\Swift_Image::fromPath('images/Logo_complet.png'));
            // Corps du message
            $message->setBody(
                $this->renderer->render(
                    'emails/EventLoisirNotification.html.twig',['imageurl'=>$image]
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }
    public function notifyFormation($emailSource,$emailDestination,$idFormation,FormationRepository $formationRepository)
    {


        // On construit le mail

        $message = (new Swift_Message('Mon blog - Nouvelle Formation détécté'))
            // Expéditeur
            ->setFrom($emailSource)
            // Destinataire
            ->setTo($emailDestination);
        $image= $message->embed(\Swift_Image::fromPath('images/Logo_complet.png'));

            // Corps du message
            $message->setBody(
                $this->renderer->render(
                    'emails/FormationNotification.html.twig',['imageurl'=>$image]
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);

    }
}