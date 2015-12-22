<?php

namespace Manager;

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
                $generatedRoutes = [];
                foreach ($data['params'] as $params) {
                    $params['_locale'] = $this->configuration['_locale'];
                    $generatedRoutes[] = $this->urlGenerator->generate($routeName, $params);
                }

                foreach ($generatedRoutes as $route) {
                    $url['loc'] = $route;
                    $urls[] = $url;
                }
            } else {
                $url['loc'] = $this->urlGenerator->generate($routeName, array(
                    '_locale' => $this->configuration['_locale']
                ));
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
}
