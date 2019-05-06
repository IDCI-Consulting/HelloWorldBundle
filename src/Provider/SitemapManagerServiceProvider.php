<?php

namespace App\Provider;

use Manager\SitemapManager;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Sitemap manager component Provider.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class SitemapManagerServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app An Container instance
     */
    public function register(Container $app)
    {
        $app['sitemap_manager'] = function () use ($app) {
            return new SitemapManager($app['url_generator'], $app['config']['sitemap']);
        };
    }
}
