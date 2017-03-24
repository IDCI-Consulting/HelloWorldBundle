<?php

namespace Provider;

use Generator\I18nRouteGenerator;
use Generator\MetaTagsGenerator;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Meta tags generator component Provider.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class MetaTagsGeneratorServiceProvider implements ServiceProviderInterface
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
        $app['meta_tags_generator'] = function ($app) {
            $metatagsGenerator = new MetaTagsGenerator();

            return $metatagsGenerator;
        };
    }
}
