<?php

namespace App\Provider;

use Generator\I18nRouteGenerator;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Internationalization route generator component Provider.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class I18nRouteGeneratorServiceProvider implements ServiceProviderInterface
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
        $app['i18n_route_generator'] = function ($app) {
            $i18nRouteGenerator = new I18nRouteGenerator($app['routes'], $app['url_generator']);
            $i18nRouteGenerator->setLanguages($app['i18n_route_generator.languages']);

            return $i18nRouteGenerator;
        };
    }
}
