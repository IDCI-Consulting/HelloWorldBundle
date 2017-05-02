<?php

/**
 * Application
 */

use Provider\ContactManagerServiceProvider;
use Provider\CourseManagerServiceProvider;
use Provider\MarkdownParserServiceProvider;
use Provider\I18nRouteGeneratorServiceProvider;
use Provider\SnappyServiceProvider;
use Provider\YamlConfigServiceProvider;
use Provider\FinderServiceProvider;
use Provider\SitemapManagerServiceProvider;
use Provider\CvManagerServiceProvider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Application();

$app->register(new RoutingServiceProvider());
$app->register(
    new TranslationServiceProvider(),
    array(
        'locale' => 'en',
    )
);
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new I18nRouteGeneratorServiceProvider());
$app->register(new MarkdownParserServiceProvider());
$app->register(new SwiftmailerServiceProvider());
$app->register(new ContactManagerServiceProvider());
$app->register(new CourseManagerServiceProvider());
$app->register(new CvManagerServiceProvider());
$app->register(new YamlConfigServiceProvider(__DIR__ . '/../config/config.yml'));
$app->register(new SnappyServiceProvider(), array(
    'snappy.image_binary' => '/usr/bin/wkhtmltoimage',
    'snappy.pdf_binary'   => '/usr/bin/wkhtmltopdf',
));
$app->register(new FinderServiceProvider());
$app->register(new SitemapManagerServiceProvider());
$app->register(new \Provider\MetaTagsGeneratorServiceProvider());

$app['snappy.pdf_options'] = array(
    'encoding'   => 'UTF-8',
);

$app['swiftmailer.options'] = $app['config']['swiftmailer'];

$app['menu.options'] = $app['config']['menu'];

$app['i18n_route_generator.languages'] = $app['config']['i18n_route_generator']['languages'];

$app['translator.messages'] = array(
    'fr' => 'Resources/messages.fr.yml',
);

$app['translator'] = $app->extend('translator', function ($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/Resources/translations/messages.fr.yml', 'fr');
    $translator->addResource('yaml', __DIR__.'/Resources/translations/messages.en.yml', 'en');
    $translator->addResource('yaml', __DIR__.'/Resources/translations/validators.fr.yml', 'fr', 'validators');

    return $translator;
});

$app['twig'] = $app->extend(
    'twig',
    function ($twig, $app) {
        // add custom globals, filters, tags, ...

        $twig->addGlobal('menu', $app['menu.options']);
        $twig->addGlobal('idci_code_writer', json_encode($app['config']['idci_code_writer']));

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

        $twig->addFunction(
            new \Twig_SimpleFunction(
                'file_get_contents',
                function ($filePath) use ($app) {
                    return file_get_contents(sprintf('%s/../%s', __DIR__, $filePath));
                }
            )
        );

        $twig->addFunction(
            new \Twig_SimpleFunction(
                'file_exists',
                function ($filePath) use ($app) {
                    return file_exists(sprintf('%s/../%s', __DIR__, $filePath));
                }
            )
        );

        $twig->addFunction(
            new \Twig_SimpleFunction(
                'getAge',
                function ($birthday, $format = 'Y-m-d') {
                    if (is_string($birthday)) {
                        $birthday = \DateTime::createFromFormat($format, $birthday);
                    }

                    if (!($birthday instanceof \DateTime)) {
                        throw new \RuntimeException('The birthday is not a valid DateTime');
                    }

                    return $birthday
                        ->diff(new \DateTime('now'))
                        ->y
                    ;
                }
            )
        );

        return $twig;
    }
);

/***************
 * Middlewares *
 ***************/

$hideContactLink = function (Request $request, Application $app) {
    $hideContactLink = false;

    if ($request->attributes->get('_route') === 'contact') {
        $hideContactLink = true;
    }

    $app['twig']->addGlobal('hide_contact_link', $hideContactLink);
};

$buildLocaleLinks = function (Request $request, Application $app) {
    $app['translator']->setLocale($request->get('_locale'));
    $i18nRoutes = $app['i18n_route_generator']->generate($request);

    $app['twig']->addGlobal('i18n_routes', $i18nRoutes);
};

$buildAsideMenu = function (Request $request, Application $app) {
    $text = $app['twig']->render(sprintf('pages/%s.html.twig', $request->get('_route')));

    $asideMenu = array();

    // Get all sections in the DOM with an id
    preg_match_all('/(?<section><section[ ]*id.*<\/section>)/siU', $text, $matched_sections);

    /*
     * Retrieve the ids and the text (inside h2 tag which is child of header)
     */
    foreach ($matched_sections['section'] as $i => $section) {
        preg_match_all('/<section[ ]*id=\\"(?<id>.+)\\".*<h2.*>(?<title>.*)</siU', $section, $matches);
        foreach ($matches['id'] as $j => $id) {
            $asideMenu[$id] = $matches['title'][$j];
        }
    }

    $app['twig']->addGlobal('aside_menu', $asideMenu);
};

$buildTabsCourseMenu = function (Request $request, Application $app) {
    $locale = $request->attributes->get('_locale');

    $app['finder']
        ->files()
        ->name('*_'.$locale.'.md')
        ->in(__DIR__.'/../templates/contents/courses/');
    $tabsCourseMenu = array();

    foreach ($app['finder'] as $file) {
        $content = $file->getContents();

        $matches = $app['course_manager']->matchContent($content);

        $title = strtolower(trim($matches['title'][0]));

        $description = trim($matches['description'][0]);

        if (strlen($description) !== 0) {
            $tabsCourseMenu[$title]['description'] = $description;
        }

        $courses = array();
        foreach ($matches['day'] as $i => $day) {
            $courses[$day] = $matches['content'][$i];
        }

        $tabsCourseMenu[$title]["courses"] = $courses;
    }

    $app['twig']->addGlobal('tabs_course_menu', $tabsCourseMenu);
};

$buildBlogSlide = function (Request $request, Application $app) {
    $locale = $request->get('_locale');
    $articles = $app['config']['blog'][$locale]['articles'];

    usort($articles, function ($article1, $article2) {
        $article1['date'] = date_create_from_format('d/m/Y', $article1['date']);
        $article2['date'] = date_create_from_format('d/m/Y', $article2['date']);

        return $article2['date']->getTimestamp() - $article1['date']->getTimestamp();
    });

    $lastArticles = array_slice($articles, 0, 5, true);

    foreach ($lastArticles as $key => $article) {
      $matches = array();
      $content = $app['twig']->render(sprintf('contents/blog/%s/%s.md', $locale, $article['file']), array());

      // Get a summary from article.
      $pattern = "/^(?!#)(?!!)((.)\W*){160} ((\w+\b)){1}/m";
      preg_match($pattern, $content, $matches);

      if ($matches[0]) {
        $matches[0] = sprintf('%s...', $matches[0]);
        $matches[0] = $app['markdown']->transform($matches[0]);
      }

      $article['summary'] = $matches[0] ?: "...";

      $lastArticles[$key] = $article;
    }

    $app['twig'] -> addGlobal('last_articles', $lastArticles);
};

$buildArticlesList = function (Request $request, Application $app) {
    $locale = $request->get('_locale');
    $articlesByCategories = array();
    $categories = $app['config']['blog'][$locale]['categories'];
    $articles = $app['config']['blog'][$locale]['articles'];

    foreach ($categories as $index => $category) {
        $articlesByCategories[$category] = array();

        foreach ($articles as $key => $article) {
            $article['date'] = date_create_from_format('d/m/Y', $article['date']);
            if (in_array($category, $article['categories'])) {
                array_push($articlesByCategories[$category], $article);
            }
        }

        usort($articlesByCategories[$category], function ($article1, $article2) {
            if ($article1['date'] == $article2['date']) {
                return 0;
            }

            return ($article1['date'] < $article2['date']) ? 1 : -1;
        });
    }

    $app['twig']->addGlobal('articles_by_categories', $articlesByCategories);
};

$buildPartnersFromJson = function (Request $request, Application $app) {
    $locale = $request->get('_locale');
    $decodedPartners = array();

    $partners = json_decode(file_get_contents(sprintf('%s/Resources/public/partners.json', __DIR__)), true);

    foreach ($partners as $partner) {
        $partner['description'] = $partner['description'][$locale];
        $decodedPartners[] = $partner;
    }

    $app['twig']->addGlobal('partners', $decodedPartners);
};

$app->after(function (Request $request, Response $response) {
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('X-Frame-Options', 'DENY');
});

return $app;
