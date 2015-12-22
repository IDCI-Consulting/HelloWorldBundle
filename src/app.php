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

$app = new Application();

$app->register(new RoutingServiceProvider());
$app->register(
    new TranslationServiceProvider(),
    array(
        'locale' => 'fr',
        'locale_fallbacks' => array('fr'),
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
$app->register(new YamlConfigServiceProvider(__DIR__ . '/../config/config.yml'));
$app->register(new SnappyServiceProvider(), array(
    'snappy.image_binary' => '/usr/local/bin/wkhtmltoimage',
    'snappy.pdf_binary'   => '/usr/local/bin/wkhtmltopdf',
));
$app->register(new FinderServiceProvider());
$app->register(new SitemapManagerServiceProvider());

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
    //$translator->addResource('yaml', __DIR__.'/Resources/translations/messages.en.yml', 'en');
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
                'file_exists',
                function ($filePath) use ($app) {
                    return file_exists(sprintf('%s/../%s', __DIR__, $filePath));
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

        $title = trim($matches['title'][0]);

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

$buildCv = function (Request $request, Application $app) {
    $name = $request->attributes->get('name');

    $content = $app['twig']->render(sprintf('contents/cv/%s.md', $name));

    $content = preg_replace('/[^#]###[^#]/', '=### ', $content);
    $content .= '=';

    preg_match_all("/#{3}(?<content>.*)=/sU", $content, $matches);

    $htmlCv = sprintf('<div class="%s">', $name);
    foreach ($matches['content'] as $content) {
        $htmlCv .= '<section markdown="1" class="cv-part">'.$app['markdown']->transform($content).'</section>';
    }
    $htmlCv .= '</div>';

    $app['twig']->addGlobal('html_cv', $htmlCv);
};

$buildRealisations = function (Request $request, Application $app) {
    $locale = $request->attributes->get('_locale');

    $app['finder']
        ->files()
        ->name('*_'.$locale.'.md')
        ->in(__DIR__.'/../templates/contents/activities/');

    $realisations = array();

    foreach ($app['finder'] as $file) {
        // Decode into utf8
        $content = $file->getContents();

        $realisations[] = $app['markdown']->transform($content);
    }

    $app['twig']->addGlobal('realisations', $realisations);
};

$buildBlogSlide = function (Request $request, Application $app) {
    $locale = $request->get('_locale');
    $articles = $app['config']['blog'][$locale]['articles'];

    foreach ($articles as $key => $article) {
        $article['date'] = date_create_from_format('d/m/Y', $article['date']);
    }

    usort($articles, function ($article1, $article2) {
        if ($article1['date'] == $article2['date']) {
            return 0;
        }

        return ($article1['date'] < $article2['date']) ? 1 : -1;
    });

    $app['twig']->addGlobal('last_articles', $articles);
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

//$buildArticleMenu = function (Request $request, Application $app) {
//    $text = $app['twig']->render(sprintf(
//        'contents/blog/%s/%s.md',
//        $request->get('_locale'),
//        $request->get('file')
//    ));
//    # Remove UTF-8 BOM, if present.
//    $text = preg_replace('{^\xEF\xBB\xBF}', '', $text);
//
//    # Standardize line endings:
//    #   DOS to Unix and Mac to Unix
//    $text = preg_replace('{\r\n?}', "\n", $text);
//
//    # atx-style headers:
//    # # Header 1        {#header1}
//    # ## Header 2       {#header2}
//    # ## Header 2 with closing hashes ##  {#header3}
//    # ...
//    # ###### Header 6   {#header2}
//    #
//    preg_match_all('{
//    ^(?<level>\#{1,6})
//    [ ]*
//    (?<title>.+?)
//    \#*
//    (?:[ ]+\{\#(?<href>[-_:[:alnum:]]+)\})?
//    [ ]*
//    \n+
//  }uxm', $text, $matches);
//
//    $menu = '<ul>';
//    foreach($matches['title'] as $i => $title) {
//        $menu .= sprintf('<li class="level%d"><a href="#%s">%s</a></li>',
//            strlen($matches['level'][$i]),
//            $matches['href'][$i],
//            trim($title)
//        );
//    }
//
//    $menu .= '</ul>';
//
//    $app['twig']->addGlobal('article_summary', $menu);
//};

return $app;
