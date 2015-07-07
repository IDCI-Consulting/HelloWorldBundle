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
                    'i18n_routes' => $i18nRoutes,
                    'menu' => $app['menu.options']
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
                    'i18n_routes' => $i18nRoutes,
                    'menu' => $app['menu.options']
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
                    'i18n_routes' => $i18nRoutes,
                    'menu' => $app['menu.options']
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
                    'i18n_routes' => $i18nRoutes,
                    'menu' => $app['menu.options']
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
                    'i18n_routes' => $i18nRoutes,
                    'menu' => $app['menu.options']
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
                    'i18n_routes' => $i18nRoutes,
                    'menu' => $app['menu.options']
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
                    'i18n_routes' => $i18nRoutes,
                    'menu' => $app['menu.options']
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

            $path     = __DIR__.'/Resources/markdown/';
            $fileName = 'cv-' . $name . '.md';
            $filePath = $path . $fileName;

            if (file_exists($filePath)) {
                $cv = file_get_contents($filePath);
            } else {
                throw new NotFoundHttpException(
                    sprintf(
                        '%s\'s cv is not found',
                        $name
                    )
                );
            }

            if ($_format === 'md') {
                // take into account line breaks
                $cv = nl2br($cv);

                return $app['twig']->render(
                    'partials/cvRaw.html.twig',
                    array(
                        'format' => $_format,
                        'cv'     => $cv
                    )
                );
            }

            $cv = $app['markdown']->transform($cv);

            if ($_format === 'html') {
                return $app['twig']->render(
                    'partials/cvRaw.html.twig',
                    array(
                        'format' => $_format,
                        'cv'     => $cv
                    )
                );
            }

            if ($_format === 'pdf') {
                $pdf = $app['snappy.pdf']->getOutputFromHtml($app['twig']->render(
                    'partials/cvRaw.html.twig',
                    array(
                        'cv' => $cv
                    )
                ));

                $response = new Response($pdf);
                $response->headers->set('Content-Type', 'application/pdf');

                return $response;
            }

            return $app['twig']->render(
                'pages/cv.html.twig',
                array(
                    'i18n_routes' => $i18nRoutes,
                    'menu' => $app['menu.options'],
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
        /*if ($app['debug']) {
            return;
        }*/

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
                        'menu' => $app['menu.options'],
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
