<?php

namespace Provider;

use Silex\Application;
use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;
use SilexMarkdown\MarkdownExtension\MarkdownTwigExtension;

class MarkdownParserServiceProvider implements ServiceProviderInterface
{
    public function boot(Application $app)
    {
    }

    public function register(Container $app)
    {
        $app['markdown'] = function () use ($app) {
            $features = isset($app['markdown.features']) ? $app['markdown.features'] : array();

            return new MarkdownParser($features);
        };

        if (isset($app['twig'])) {
            $app['twig'] = $app->extend('twig', function ($twig, $app) {
                $twig->addExtension(new MarkdownTwigExtension($app['markdown']));

                return $twig;
            });
        }
    }
}
