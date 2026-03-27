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
        private array $extraData,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data['hello_world.app_name'] = $this->appName;
        $this->data['hello_world.app_version'] = $this->appVersion;
        $this->data['hello_world.extra_data'] = $this->extraData;
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

    public function getExtraData(): array
    {
        return $this->data['hello_world.extra_data'];
    }

    public function addExtraData(array $data): void {
        $key = array_search($data['label'], array_column($this->extraData, 'label'));

        if (false !== $key) {
            $this->extraData[$key] = $data;

            return;
        }

        $this->extraData[] = $data;
    }
}