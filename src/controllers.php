<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Form\Type\ContactType;

//Request::setTrustedProxies(array('127.0.0.1'));

$intlApp = $app['controllers_factory'];
$app->mount('/{_locale}', $intlApp);

// Home page
$intlApp->get(
    '/',
    function (Request $request, $_locale) use ($app) {

        $form = $app['form.factory']->createBuilder(new ContactType())->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            var_dump('VALID', $form->getData());
        }

        return $app['twig']->render('pages/index.html.twig', array('form' => $form->createView()));
    }
)
->bind('homepage')
->before($before);

// Company page
$intlApp->get(
    '/company',
    function (Request $request, $_locale) use ($app) {
        return $app['twig']->render('pages/company.html.twig', array());
    }
)
->bind('company');

// Team page
$intlApp->get(
    '/team',
    function (Request $request, $_locale) use ($app) {
        return $app['twig']->render('pages/team.html.twig', array());
    }
)
->bind('team');

// Activities page
$intlApp->get(
    '/activity',
    function (Request $request, $_locale) use ($app) {
        return $app['twig']->render('pages/activities.html.twig', array());
    }
)
->bind('activities');

// Partners page
$intlApp->get(
    '/partners',
    function (Request $request, $_locale) use ($app) {
        return $app['twig']->render('pages/partners.html.twig', array());
    }
)
->bind('partners');

// Blog page
$intlApp->get(
    '/blog',
    function (Request $request, $_locale) use ($app) {
        return $app['twig']->render('pages/blog.html.twig', array());
    }
)
->bind('blog');

// Contact page
$intlApp->match(
    '/contact',
    function (Request $request, $_locale) use ($app) {
        $form = $app['form.factory']->createBuilder(new ContactType())->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            var_dump('VALID', $form->getData());
        }

        return $app['twig']->render('pages/contact.html.twig', array('form' => $form->createView()));
    }
)
->bind('contact');

$app->error(
    function (\Exception $e, Request $request, $code) use ($app) {
        if ($app['debug']) {
            return;
        }

        // 404.html, or 40x.html, or 4xx.html, or error.html
        $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
        );

        return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
    }
);
