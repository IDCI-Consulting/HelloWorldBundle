<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Form\Type\ContactType;

//Request::setTrustedProxies(array('127.0.0.1'));

$intlApp = $app['controllers_factory'];
$redirectApp = $app['controllers_factory'];

//Routing requirements
$intlApp->assert('_locale', 'fr|en');

/******************
 * App Controller *
 ******************/

// Redirect to home page according to the browser's preferred language
$redirectApp
    ->get(
        '/',
        function (Request $request) use ($app) {

            $availableLanguages = $app['i18n_route_generator.languages'];

            return $app->redirect(
                $app['url_generator']->generate(
                    'homepage',
                    array(
                        "_locale" => $request->getPreferredLanguage($availableLanguages)
                    )
                )
            );
        }
    )
    ->bind('redirect-homepage')
;

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
        '/cv/{name}.{_format}',
        function (Request $request, $_locale, $name, $_format) use ($app) {

            $app['translator']->setLocale($_locale);
            $i18nRoutes = $app['i18n_route_generator']->generate($request);

            try {
                $cv = $app['twig']->render(sprintf('cv/%s.md.twig', $name), array());
            } catch (\Exception $e) {
                throw new NotFoundHttpException(sprintf(
                    'The %s\'s cv doesn\'t exist',
                    $name
                ));
            }

            $response = new Response();

            if ($_format === 'md') {
                $response->headers->set('Content-Type', 'text/markdown');
                $response->setContent($cv);

                return $response;
            }

            $cv = $app['markdown']->transform($cv);

            if ($_format === 'html') {
                $response->setContent($cv);

                return $response;
            }

            if ($_format === 'pdf') {
                $response->headers->set('Content-Type', 'application/pdf');
                $response->setContent($app['snappy.pdf']->getOutputFromHtml($cv));

                return $response;
            }

            return $app['twig']->render(
                'pages/cv.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes,
                    'name'        => $name,
                    'cv'          => $cv
                )
            );
        }
    )
    ->assert('_format', 'md|html|pdf')
    ->value('_format', '')
    ->bind('cv')
;

$intlApp
    ->match(
        '/contact-form',
        function (Request $request, $_locale) use ($app) {

            $app['translator']->setLocale($_locale);

            $form = $app['form.factory']->createBuilder(new ContactType())->getForm();

            if ($request->getMethod() === 'POST') {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

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
                    'form' => $form->createView()
                )
            );
        },
        "GET|POST"
    )
    ->bind('contactForm')
;

$app->error(
    function (\Exception $e, Request $request, $code) use ($app) {
        if ($app['debug']) {
            return;
        }

        $app['translator']->setLocale($request->get('_locale'));
        $i18nRoutes = $app['i18n_route_generator']->generate($request);

        // 404.html, or 40x.html, or 4xx.html, or error.html
        $templates = array(
            'errors/'.$code.'.html.twig',
            'errors/'.substr($code, 0, 2).'x.html.twig',
            'errors/'.substr($code, 0, 1).'xx.html.twig',
            'errors/default.html.twig',
        );

        return new Response(
            $app['twig']
                ->resolveTemplate($templates)
                ->render(
                    array(
                        'i18n_routes' => $i18nRoutes,
                        'code'        => $code,
                        'message'     => $e->getMessage()
                    )
                ),
            $code
        );
    }
);

$app->mount('/{_locale}', $intlApp);
$app->mount('/', $redirectApp);
