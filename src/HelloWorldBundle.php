<?php

namespace IDCI\Bundle\HelloWorldBundle;

use IDCI\Bundle\HelloWorldBundle\DependencyInjection\Compiler\DebugDataCompilerPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class HelloWorldBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new DebugDataCompilerPass());
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('app')
                    ->isRequired()
                    ->children()
                        ->scalarNode('name')->isRequired()->end()
                        ->scalarNode('version')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('additional_datas')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('value')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter('hello_world_bundle.app_name', $config['app']['name']);
        $builder->setParameter('hello_world_bundle.app_version', $config['app']['version']);
        $builder->setParameter('hello_world_bundle.additional_datas', $config['additional_datas']);

        $container->import('../config/services.yaml');
    }
}