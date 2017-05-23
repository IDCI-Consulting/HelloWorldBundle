<?php

namespace Tests;

use Silex\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    protected $config;

    public function createApplication()
    {
        $app = require __DIR__.'/../app.php';
        require __DIR__.'/../../config/prod.php';
        require __DIR__.'/../controllers.php';

        // Useful for the contact page only (flash bag messages)
        $app['session.test'] = true;

        $this->config = $app['config'];

        return $app;
    }

    public function testPageIsSuccessful()
    {
        foreach ($this->getUrls() as $url) {
            echo $url."\n";
            $client = $this->createClient();
            $client->request('GET', $url);

            $this->assertTrue($client->getResponse()->isSuccessful());
        }
    }

    /**
     * Get the urls to test
     *
     * @return array
     */
    private function getUrls()
    {
        $urls = array(
            '/fr/',
            '/en/',
            '/fr/courses',
            '/en/courses',
            '/fr/team',
            '/en/team',
            '/fr/activities',
            '/en/activities',
            '/fr/partners',
            '/en/partners',
            '/fr/blog',
            '/en/blog',
            '/fr/contact',
            '/en/contact',
            '/fr/mentions',
            '/en/mentions',
            '/fr/sitemap',
            '/en/sitemap',
        );

        $this->addArticleUrls($urls, 'fr');
        $this->addArticleUrls($urls, 'en');
        $this->addCvUrls($urls);

        return $urls;
    }

    /**
     * Add the blog article urls to the urls array for a given locale
     *
     * @param $urls
     * @param $locale
     */
    private function addArticleUrls(&$urls, $locale)
    {
        $articles = $this->config['blog'][$locale]['articles'];

        foreach ($articles as $article) {
            // Remove .md extension
            $url = '/' . $locale . '/article/' . $article['file'];
            array_push($urls, $url);
        }
    }

    /**
     * Add the cv urls to the urls array, found in the cv templates directory
     *
     * Ie: /en//cv/idci/john_doe_en
     *     /en//cv/idci/john_doe_en.html
     *     /en//cv/idci/john_doe_en.pdf
     *     /en//cv/idci/john_doe_en.md
     *     ...
     *
     * @param $urls
     */
    private function addCvUrls(&$urls)
    {
        $extensions = array('.pdf', '.html', '.md', '');

        $cvs = array_diff(
            scandir(__DIR__ . '/../../templates/contents/cv/'),
            array('.', '..')
        );

        foreach ($cvs as $cv) {
            // Remove .md extension
            $cv = substr($cv, 0, -3);

            // Get the local at the end of the string
            $locale = substr($cv, -2);

            // Remove _{$locale}
            $cv = substr($cv, 0, -3);

            $url = sprintf('/%s/cv/idci/%s', $locale, $cv);

            foreach ($extensions as $extension) {
                array_push($urls, sprintf('%s%s', $url, $extension));
            }
        }
    }
}
