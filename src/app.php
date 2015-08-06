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
        'locale' => 'en',
        'locale_fallbacks' => array('en'),
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

        return $twig;
    }
);

/***************
 * Middlewares *
 ***************/

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
        ->name('*_'.$locale.'.md.twig')
        ->in(__DIR__.'/../templates/contents/courses/');
    $tabsCourseMenu = array();

    foreach ($app['finder'] as $file) {
        // Decode into utf8
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

    $content = $app['twig']->render(sprintf('contents/cv/%s.md.twig', $name));

    $content = preg_replace('/[^#]###[^#]/', '=### ', $content);
    $content .= '=';

    preg_match_all("/#{3}(?<content>.*)=/sU", $content, $matches);

    $htmlCv = '';
    foreach ($matches['content'] as $content) {
        $htmlCv .= '<section markdown="1">'.$app['markdown']->transform($content).'</section>';
    }

    $app['twig']->addGlobal('html_cv', $htmlCv);
};

return $app;
