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
    private array $contactRecipientsAddresses;

    public function __construct(MailerInterface $mailer, Environment $twig, array $contactRecipientsAddresses)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->contactRecipientsAddresses = $contactRecipientsAddresses;
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
        dd($this->contactRecipientsAddresses);
        $email = (new Email())
            ->to(...$this->contactRecipientsAddresses)
            ->subject('IDCI Contact')
            ->html(
                $this->twig->render(
                    'emails/contact_idci_notification.html.twig',
                    [
                        'form_data' => $event->getFormData(),
                    ]
                )
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
                $this->twig->render(
                    'emails/contact_sender_notification.html.twig',
                    [
                        'form_data' => $event->getFormData(),
                    ]
                )
            )
        ;

        $this->mailer->send($email);
    }
}