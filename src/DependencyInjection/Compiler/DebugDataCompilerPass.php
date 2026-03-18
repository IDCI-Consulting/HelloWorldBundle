<?php

namespace IDCI\Bundle\HelloWorldBundle\DependencyInjection\Compiler;

use IDCI\Bundle\HelloWorldBundle\DataCollector\HelloWorldDataCollector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DebugDataCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(HelloWorldDataCollector::class)) {
            return;
        }

        $additionalDatas = $container->getParameter('hello_world_bundle.additional_datas');
        $helloWorldDataCollectorDefinition = $container->findDefinition(HelloWorldDataCollector::class);

        foreach ($additionalDatas as $data) {
            $helloWorldDataCollectorDefinition->addMethodCall('addData', [$data]);
        }
    }
}
