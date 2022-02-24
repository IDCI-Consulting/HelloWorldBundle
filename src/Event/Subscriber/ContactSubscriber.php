<?php

namespace App\Event\Subscriber;

use App\Event\ContactEvent;
use App\Event\ContactEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class ContactSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            ContactEvents::CONTACT_FORM_SUBMITTED => [
                ['notifyIdciByEmail', 10],
                ['notifySenderByEmail', 20],
            ],
        ];
    }

    public function notifyIdciByEmail(ContactEvent $event)
    {
        $email = (new Email())
            ->to($_ENV['MAILER_RECIPIENT_ADDRESS'])
            ->subject('IDCI Contact')
            ->html(
                $this->twig->render('emails/idci_email.html.twig')
            )
        ;

        $this->mailer->send($email);
    }

    public function notifySenderByEmail(ContactEvent $event)
    {
        $email = (new Email())
            ->to($event->getFormData("email"))
            ->subject('IDCI Contact')
            ->html(
                $this->twig->render('emails/sender_email.html.twig')
            )
        ;

        $this->mailer->send($email);
    }
}