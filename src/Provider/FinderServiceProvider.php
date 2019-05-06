<?php

namespace App\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Symfony\Component\Finder\Finder;

/**
 * Symfony Finder component Provider.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class FinderServiceProvider implements ServiceProviderInterface
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
        // finder factory configuration
        $app['finder'] = function () use ($app) {
            return new Finder();
        };
    }
}
