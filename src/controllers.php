<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

// Home page
$app->get('/', function () use ($app) {
    return $app['twig']->render('pages/index.html.twig', array());
})
->bind('homepage')
;

// Company page
$app->get('/company', function () use ($app) {
    return $app['twig']->render('pages/company.html.twig', array());
})
->bind('company')
;

// Team page
$app->get('/team', function () use ($app) {
    return $app['twig']->render('pages/team.html.twig', array());
})
->bind('team')
;

// Activities page
$app->get('/activity', function () use ($app) {
    return $app['twig']->render('pages/activities.html.twig', array());
})
->bind('activities')
;

// Partners page
$app->get('/partners', function () use ($app) {
    return $app['twig']->render('pages/partners.html.twig', array());
})
->bind('partners')
;

// Blog page
$app->get('/blog', function () use ($app) {
    return $app['twig']->render('pages/blog.html.twig', array());
})
->bind('blog')
;

// Contact page
$app->match('/contact', function (Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder(new \Form\Type\ContactType())->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        var_dump('VALID', $form->getData());
    }

    return $app['twig']->render('pages/contact.html.twig', array('form' => $form->createView()));
})
->bind('contact')
;

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
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
});
