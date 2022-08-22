<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class HeaderVersionListener
{
    private $appVersion;

    public function __construct(string $appVersion)
    {
        $this->appVersion = $appVersion;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $event->getResponse()->headers->set('X-App-Version', $this->appVersion);
    }
}