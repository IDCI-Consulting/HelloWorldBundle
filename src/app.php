<?php

/**
 * Application
 */

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;

$app = new Application();
$app->register(new RoutingServiceProvider());
$app->register(
    new TranslationServiceProvider(),
    array(
        'locale' => 'en',
        'locale_fallbacks' => array('en'),
    )
);
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app['twig'] = $app->extend(
    'twig',
    function ($twig, $app) {
        // add custom globals, filters, tags, ...

        $twig->addFunction(
            new \Twig_SimpleFunction(
                'asset',
                function ($asset) use ($app) {
                    return $app['request_stack']->getMasterRequest()->getBasepath().'/'.$asset;
                }
            )
        );

        $twig->addFunction(
            new \Twig_SimpleFunction(
                'getRevFilename',
                function ($filename) use ($app) {
                    $json = file_get_contents(sprintf('%s/Resources/manifest/rev-manifest.json', __DIR__));
                    $jsonArray = json_decode($json, true);

                    return $jsonArray[$filename];
                }
            )
        );

        return $twig;
    }
);

return $app;
