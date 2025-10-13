<?php

namespace IDCI\Bundle\HelloWorldBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class HelloWorldDataCollector extends DataCollector
{
    public function __construct(
        private string $appName,
        private string $appVersion,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data['hello_world.app_name'] = $this->appName;
        $this->data['hello_world.app_version'] = $this->appVersion;
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getName(): string
    {
        return 'hello_world.data_collector';
    }

    public function getAppName(): string
    {
        return $this->data['hello_world.app_name'];
    }

    public function getAppVersion(): string
    {
        return $this->data['hello_world.app_version'];
    }
}