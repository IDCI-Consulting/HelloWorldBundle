<?php

namespace App\Event\Subscriber;

use App\Event\ContactEvent;
use App\Event\ContactEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class ContactSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            ContactEvents::CONTACT_FORM_SUBMITTED => [
                ['sendContactEmail', 10],
                ['sendContactEmailValidation', 20],
            ],
        ];
    }

    public function sendContactEmail(ContactEvent $event)
    {
        $email = (new Email())
            ->to($event->getFormData("email"))
            ->subject('Vous avez une nouvelle demande de contact !')
            ->html('<p>On vous a contacté récemment</p>')
        ;

        $this->mailer->send($email);
    }

    public function sendContactEmailValidation(ContactEvent $event)
    {
        $email = (new Email())
            ->to($event->getFormData("email"))
            ->subject('Vous nous avez contacté !')
            ->text('Nous avons bien reçu votre demande')
            ->html('<p>Nous allons en prendre connaissance et revenir vers vous !</p>')
        ;

        $this->mailer->send($email);
    }
}