<?php

namespace App\Event\Subscriber;

use App\Event\ContactEvent;
use App\Event\ContactEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContactSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ContactEvents::CONTACT_FORM_SUBMITTED => [
                ['sendContactEmail', 8],
            ],
        ];
    }

    public function sendContactEmail()
    {
        dd('email to send now');
    }
}