<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Form\Type\ContactType;

//Request::setTrustedProxies(array('127.0.0.1'));

$intlApp = $app['controllers_factory'];
$baseApp = $app['controllers_factory'];
$apiApp = $app['controllers_factory'];

//Routing requirements
$intlApp->assert('_locale', 'fr|en');

/*******************
 * App Controllers *
 *******************/

// Redirect to home page according to the browser's preferred language
$baseApp
    ->get(
        '/',
        function (Request $request) use ($app) {

            $availableLanguages = array();

            foreach ($app['i18n_route_generator.languages'] as $locale => $language) {
                $availableLanguages[] = $locale;
            }

            return $app->redirect(
                $app['url_generator']->generate(
                    'index',
                    array(
                        "_locale" => $request->getPreferredLanguage($availableLanguages)
                    )
                )
            );
        }
    )
    ->bind('redirect-index')
;

// Home page
$intlApp
    ->get(
        '/',
        function (Request $request, $_locale) use ($app) {

            return $app['twig']->render('pages/index.html.twig');
        }
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->before($buildAsideMenu)
    ->bind('index')
;

// Mentions page
$intlApp
    ->get(
        '/mentions',
        function (Request $request, $_locale) use ($app) {

            return $app['twig']->render('pages/mentions.html.twig');
        }
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->bind('mentions')
;

// Company page
$intlApp
    ->get(
        '/company',
        function (Request $request, $_locale) use ($app) {

            return $app['twig']->render('pages/company.html.twig');
        }
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->bind('company')
;

// Team page
$intlApp
    ->get(
        '/team',
        function (Request $request, $_locale) use ($app) {

            return $app['twig']->render('pages/team.html.twig');
        }
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->before($buildAsideMenu)
    ->bind('team')
;

// Activities page
$intlApp
    ->get(
        '/activities',
        function (Request $request, $_locale) use ($app) {
            return $app['twig']->render('pages/activities.html.twig', array(
                'achievements' => $app['config']['achievements']
            ));
        }
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->bind('activities')
;

// Partners page
$intlApp
    ->get(
        '/partners',
        function () use ($app) {
            return $app['twig']->render('pages/partners.html.twig');
        }
    )
    ->before($buildPartnersFromJson)
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->before($buildAsideMenu)
    ->bind('partners')
;

// Course page
$intlApp
    ->get(
        '/courses',
        function (Request $request, $_locale) use ($app) {

            return $app['twig']->render('pages/courses.html.twig');
        }
    )
    ->before($hideContactLink)
    ->before($buildTabsCourseMenu)
    ->before($buildLocaleLinks)
    ->before($buildAsideMenu)
    ->bind('courses')
;

$intlApp
    ->get(
        '/course/{name}.{_format}',
        function (Request $request, $_locale, $name, $_format) use ($app) {

            try {
                $course = $app['twig']->render(sprintf('contents/courses/%s_%s.md', $name, $_locale), array());
            } catch (\Exception $e) {
                throw new NotFoundHttpException(sprintf(
                    'The %s\'s course doesn\'t exist',
                    $name
                ));
            }

            $response = new Response();

            if ('md' === $_format) {
                $response->headers->set('Content-Type', 'text/markdown');
                $response->setContent($course);

                return $response;
            }
            $matches = $app['course_manager']->matchContent($course);

            $matchedCourse = '<h1>'.strtoupper($matches['title'][0]).'</h1>';
            foreach ($matches['day'] as $i => $day) {
                $day = $app['translator']->trans($day);

                $matchedCourse .= sprintf(
                    '##%s %s',
                    $day,
                    $matches['content'][$i]
                );
            }
            $course = $app['twig']->render(
                'partials/courses/output.html.twig',
                array(
                    'course' => $app['markdown']->transform($matchedCourse),
                    'format' => $_format,
                )
            );

            if ('html' === $_format) {
                return $course;
            }

            if ($_format === 'pdf') {
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Disposition', sprintf('filename="IDCI_%s.pdf"', $name));

                $response->setContent($app['snappy.pdf']->getOutputFromHtml($course));

                return $response;
            }

            return $app['twig']->render('pages/courses.html.twig');
        }
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->assert('_format', 'md|html|pdf')
    ->bind('course')
;

// Blog page
$intlApp
    ->get(
        '/blog',
        function (Request $request, $_locale) use ($app) {

            return $app['twig']->render('pages/blog.html.twig');
        }
    )
    ->before($buildBlogSlide)
    ->before($buildArticlesList)
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->before($buildAsideMenu)
    ->bind('blog')
;

// Article page
$intlApp
    ->get(
        '/article/{file}',
        function (Request $request, $_locale, $file) use ($app) {
            $articleConfiguration = array();
            $isDisplayable = false;

            foreach ($app['config']['blog'][$_locale]['articles'] as $article) {
                if ($article['file'] === $file) {
                    $articleConfiguration = $app['meta_tags_generator']->buildOpenGraphMeta(
                        $article['title'],
                        $app['url_generator']->generate('index', array(
                            "_locale" => $_locale,
                            "file"    => $article['file']
                        )),
                        'article',
                        $article['image']
                    );

                    $isDisplayable = true;
                }
            }

            try {
                if (!$isDisplayable) {
                    throw new Exception('this page is not available.');
                }

                $article = $app['twig']->render(sprintf('contents/blog/%s/%s.md', $_locale, $file), array());
                $article = $app['markdown']->transform($article);
            } catch (\Exception $e) {
                throw new NotFoundHttpException(sprintf(
                    'An error occured: %s',
                    $e->getMessage()
                ));
            }

            return $app['twig']->render(
                'pages/article.html.twig',
                array(
                    'article' => $article,
                    'articleConfiguration' => $articleConfiguration
                )
            );
        }
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->bind('article')
;

// Contact page
$intlApp
    ->match(
        '/contact',
        function (Request $request, $_locale) use ($app) {
            $form = $app['form.factory']->createBuilder(ContactType::class)->getForm();

            if ($request->getMethod() === 'POST') {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $app['contact_manager']->sendMail($data);

                    $message = $app['translator']->trans('Your message is sent');

                    if ($request->isXmlHttpRequest()) {
                        $response = new Response();
                        $response->headers->set('X-Message', $message);
                        $response->headers->set('X-LEVEL', 'success');
                        $response->setStatusCode(Response::HTTP_CREATED);

                        return $response;
                    }

                    $app['session']->getFlashBag()->add('success', $message);

                    return $app->redirect(
                        $app['url_generator']->generate('index', array(
                            "_locale" => $_locale
                        ))
                    );
                }

                $message = $app['translator']->trans('Your message could not be sent');
            }

            $view = 'pages/contact.html.twig';

            if ($request->isXmlHttpRequest()) {
                $response = new Response();

                if ($request->getMethod() === 'POST') {
                    $view = 'partials/contactForm.html.twig';

                    if (null !== $message) {
                        $response->headers->set('X-Message', $message);
                        $response->headers->set('X-Level', 'alert');
                    }
                } else {
                    $view = 'partials/contactFormContainer.html.twig';
                }

                $response->setContent($app['twig']->render($view, array('form' => $form->createView())));

                return $response;
            }

            if (isset($message)) {
                $app['session']->getFlashBag()->add('alert', $message);
            }

            return $app['twig']->render($view, array('form' => $form->createView()));
        },
        "GET|POST"
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->bind('contact')
;

$intlApp
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->assert('_format', 'md|html|pdf|vcf|lnk')
    ->value('_format', '')
    ->get(
        '/cv/{theme}/{name}.{_format}',
        function ($_locale, $theme, $name, $_format) use ($app) {

            $response = new Response();

            if ('vcf' === $_format) {
                $response->headers->set('Content-Type', 'text/x-vcard');
                $response->setContent($app['twig']->render(sprintf('vcard/%s.vcf.twig', $name), array()));

                return $response;
            }

            if ('lnk' === $_format) {
                return $app->redirect($app['url_generator']->generate('cv', array(
                    "_locale" => $_locale,
                    "theme"   => "idci",
                    "name"    => $name,
                    "_format" => "vcf"
                )));
            }

            if ('md' === $_format) {
                $response->headers->set('Content-Type', 'text/markdown');
                $response->setContent($app['cv_manager']->buildAsMarkdown($name, $_locale));

                return $response;
            }

            $md = $app['cv_manager']->prepareMarkdownForHtml($name, $_locale);
            $cv = $app['markdown']->transform($md);

            $cvHtml = $app['twig']->render('partials/cv/output.html.twig', array(
                'cv'     => $cv,
                'theme'  => $theme,
                'format' => $_format,
                'name'   => $name
            ));

            //$article = $app['twig']->render(sprintf('contents/blog/%s/%s.md', $_locale, $file), array());
            if ('html' === $_format) {
                return $cvHtml;
            }

            if ('pdf' === $_format) {
                // Add option to remove the margin on pdf generation
                $app['snappy.pdf_options'] = array(
                    'encoding'   => 'UTF-8',
                    'margin-top' => 0,
                    'margin-right' => 0,
                    'margin-bottom' => 0,
                    'margin-left' => 0
                );

                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Disposition', sprintf('filename="IDCI_%s.pdf"', $name));
                $response->setContent($app['snappy.pdf']->getOutputFromHtml($cvHtml, $app['snappy.pdf_options']));

                return $response;
            }

            return $app['twig']->render('pages/cv.html.twig', array(
                'name' => $name,
                'cv'   => $cv,
                'theme' => $theme
            ));
        }
    )
    ->bind('cv')
;

$baseApp
    ->get(
        '/sitemap.xml',
        function (Request $request) use ($app) {
            $hostname = $request->getHost();
            $response = new Response();
            $availableLanguages = array();

            foreach ($app['i18n_route_generator.languages'] as $locale => $language) {
                $availableLanguages[] = $locale;
            }

            $locale = $request->getPreferredLanguage($availableLanguages);

            $app['sitemap_manager']->addConfiguration('_locale', $locale);

            if (($key = array_search($locale, $availableLanguages)) !== false) {
                unset($availableLanguages[$key]);
            }

            $app['sitemap_manager']->addConfiguration('other_langs', $availableLanguages);

            $urls = $app['sitemap_manager']->build();

            $response->headers->set('Content-Type', 'text/xml');
            $response->setContent($app['twig']->render('pages/sitemap.xml.twig', array(
                'urls'     => $urls,
                'hostname' => $hostname,
            )));

            return $response;
        }
    )
;

$intlApp
    ->get(
        '/sitemap',
        function (Request $request, $_locale) use ($app) {
            $hostname = $request->getHost();
            $app['sitemap_manager']->addConfiguration('_locale', $_locale);
            $urls = $app['sitemap_manager']->build();
            return $app['twig']->render('pages/sitemap.svg.twig', array(
                'urls'     => $urls,
                'hostname' => $hostname,
            ));
        }
    )
    ->before($hideContactLink)
    ->before($buildLocaleLinks)
    ->bind('sitemap')
;

$intlApp
    ->get(
        '/generate-qrcode',
        function (Request $request, $_locale) use ($app) {
            $vcfFileName = $request->query->get('vcf_file_name');
            $vcfPath = sprintf('vcard/%s.vcf.twig', $vcfFileName);

            $app['qrcode']->setText($app['url_generator']->generate('cv', array(
                "_locale" => $_locale,
                "theme"   => "idci",
                "name"    => $vcfFileName,
                "_format" => "lnk"
            ), UrlGeneratorInterface::ABSOLUTE_URL));

            return new Response(
                $app['qrcode']->get(),
                200,
                array('Content-Type' => $app['qrcode']->getContentType())
            );

            throw new NotFoundResourceException(sprintf('VCF file "%s.vcf" not found', $vcfFileName));
        }
    )
    ->bind('generate_vcard')
;

$app
    ->error(
        function (\Exception $e, Request $request, $code) use ($app) {
            if ($app['debug']) {
                return;
            }

            $availableLanguages = array();
            $routes = array();

            foreach ($app['i18n_route_generator.languages'] as $locale => $language) {
                $availableLanguages[] = $locale;

                $generatedRoute = $app['url_generator']->generate('index', array('_locale' => $locale));

                $routes[$locale] = array(
                    'route'    => $generatedRoute,
                    'language' => $language
                );
            }

            // See https://github.com/silexphp/Silex/issues/1129
            $_locale = explode('/', trim(str_replace(
                $app['request_context']->getBaseUrl(),
                '',
                $request->getRequestUri()
            ), '/'));
            $_locale = array_shift($_locale);

            if (!in_array($_locale, $availableLanguages)) {
                $_locale = $request->getPreferredLanguage($availableLanguages);
            }

            $app['request_context']->setParameters(array('_locale' => $_locale));
            $app['translator']->setLocale($_locale);

            $app['twig']->addGlobal('i18n_routes', $routes);

            // 404.html, or 4xx.html.twig, or 500.html.twig, or 5xx.html.twig or default.html.twig
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
                            'code'    => $code,
                            'message' => $e->getMessage()
                        )
                    ),
                $code
            );
        }
    )
;

$app->mount('/{_locale}', $intlApp);
$app->mount('/', $baseApp);
