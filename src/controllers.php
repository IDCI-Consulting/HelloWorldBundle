<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Form\Type\ContactType;

//Request::setTrustedProxies(array('127.0.0.1'));

$intlApp = $app['controllers_factory'];

// Home page
$intlApp
    ->match(
        '/contact-form',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            $form = $app['form.factory']->createBuilder(new ContactType())->getForm();

            if ($request->getMethod() === 'POST') {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $name = $data['name'] . ' ' . $data['firstname'];

                    $message = Swift_Message::newInstance()
                        ->setSubject('Nouvelle demande de projet')
                        ->setFrom('no-reply@idci-consulting.fr')
                        ->setTo(array('contact@idci-consulting.fr'))
                        ->setBody(
                            $app['twig']->render(
                                'pages/email.html.twig',
                                array(
                                    'company'       => $data['company'],
                                    'name'          => $data['name'],
                                    'firstName'     => $data['firstname'],
                                    'project'       => $data['project'],
                                    'phoneNumber'   => $data['phonenumber'],
                                    'email'         => $data['email'],
                                )
                            ),
                            'text/html'
                        )
                    ;

                    $app['mailer']->send($message);
                }

                return $app->redirect(
                    $app['url_generator']->generate(
                        'homepage',
                        array("_locale" => $_locale)
                    )
                );
            }

            return $app['twig']->render(
                'partials/contactForm.html.twig',
                array(
                    'form' => $form->createView(),
                    'i18n_routes' => $i18nRoutes
                )
            );
        },
        "GET|POST"
    )
    ->bind('contactForm')
;

$intlApp = $app['controllers_factory'];

//Routing requirements
$intlApp->assert('_locale', 'fr|en');

/******************
 * App Controller *
 ******************/

// Home page
$intlApp
    ->get(
        '/',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            $form = $app['form.factory']->createBuilder(new ContactType())->getForm();

            return $app['twig']->render(
                'pages/index.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes
                )
            );
        }
    )
    ->bind('homepage')
;

// Company page
$intlApp
    ->get(
        '/company',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            return $app['twig']->render(
                'pages/company.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes
                )
            );
        }
    )
    ->bind('company')
;

// Team page
$intlApp
    ->get(
        '/team',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            return $app['twig']->render(
                'pages/team.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes
                )
            );
        }
    )
    ->bind('team')
;

// Activities page
$intlApp
    ->get(
        '/activity',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            return $app['twig']->render(
                'pages/activities.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes
                )
            );
        }
    )
    ->bind('activities')
;

// Partners page
$intlApp
    ->get(
        '/partners',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            return $app['twig']->render(
                'pages/partners.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes
                )
            );
        }
    )
    ->bind('partners')
;

// Blog page
$intlApp
    ->get(
        '/blog',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            return $app['twig']->render(
                'pages/blog.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes
                )
            );
        }
    )
    ->bind('blog')
;

// Contact page
$intlApp
    ->get(
        '/contact',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            return $app['twig']->render(
                'pages/contact.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes
                )
            );
        }
    )
    ->bind('contact')
;

$intlApp
    ->get(
        '/cv/{name}',
        function (Request $request, $_locale, $name) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            return $app['twig']->render(
                'pages/cv.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes,
                    'name'        => $name
                )
            );
        }
    )
    ->bind('cv')
;

$intlApp
    ->get(
        '/cv/{name}/raw',
        function (Request $request, $name) use ($app) {

            $filePath = __DIR__.'/Resources/markdown/';
            $fileName = 'cv-' . $name . '.md';

            $cv = file_get_contents(sprintf('%s%s', $filePath, $fileName));

            $cv = $app['markdown']->transform($cv);

            return $app['twig']->render(
                'partials/cvRaw.html.twig',
                array(
                    'cv' => $cv
                )
            );
        }
    )
    ->bind('cv-raw')
;

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

$app->mount('/{_locale}', $intlApp);
