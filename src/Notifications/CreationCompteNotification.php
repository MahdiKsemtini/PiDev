<?php

namespace App\Notifications;

// On importe les classes nécessaires à l'envoi d'e-mail et à twig

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
     * @return void
     */
    public function notifyEmploi($emailSource,$emailDestination)
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
}