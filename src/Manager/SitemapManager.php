<?php

namespace App\Manager;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Acl\Exception\Exception;

class SitemapManager
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * Constructor
     *
     * @param array $configuration The sitemap's configuration
     * @param UrlGenerator $urlGenerator The url generator
     */
    public function __construct(UrlGenerator $urlGenerator, array $configuration = [])
    {
        $this->configuration = $configuration;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Has configuration
     *
     * @param string $key
     *
     * @return boolean
     */
    public function hasConfiguration($key)
    {
        return array_key_exists($key, $this->configuration);
    }

    /**
     * Get configuration
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Set configuration
     *
     * @param array $configuration The sitemap's configuration
     *
     * @return SitemapManager
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Build the sitemap
     */
    public function build()
    {
        if (!isset($this->configuration['_locale'])) {
            throw new \InvalidArgumentException('You need a locale to build the sitemap');
        }

        $routes = $this->configuration['routes'];
        $urls = array();

        foreach ($routes as $routeName => $data) {
            $url = array(
                'priority' => $data['priority'],
                'changefreq' => $data['changefreq'],
                'lastmod' => $data['lastmod']
            );


            if (array_key_exists('params', $data)) {
                foreach ($data['params'] as $params) {
                    $params['_locale'] = $this->configuration['_locale'];
                    $url['loc'] = $this->urlGenerator->generate($routeName, $params);

                    $urls[] = $url;
                }
            } else {
                $url['loc'] = $this->urlGenerator->generate($routeName, array(
                    '_locale' => $this->configuration['_locale']
                ));

                if ($this->hasConfiguration('other_langs')) {
                    foreach ($this->configuration['other_langs'] as $lang) {
                        $url['localized_locs'][] = $this->buildLocalizedLoc($routeName, $lang);
                    }
                }

                $urls[] = $url;
            }
        }

        return $urls;
    }

    /**
     * Add configuration
     *
     * @param $alias
     * @param $value
     *
     * @return SitemapManager
     */
    public function addConfiguration($alias, $value)
    {
        $this->configuration[$alias] = $value;

        return $this;
    }

    /**
     * Build localized loc
     *
     * @param string $routeName
     * @param string $locale
     * @param array  $parameters
     *
     * @return array
     */
    private function buildLocalizedLoc($routeName, $locale, array $parameters = array())
    {
        return array(
            'locale' => $locale,
            'route'  => $this->urlGenerator->generate($routeName, array_merge($parameters, array('_locale' => $locale)))
        );
    }
}
