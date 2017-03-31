<?php

namespace Provider;

use Manager\CvManager;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Cv manager component Provider.
 *
 * @author Baptiste Bouchereau <baptiste.bouchereau@idci-consulting.fr>
 */
class CvManagerServiceProvider implements ServiceProviderInterface
{
    /**
     * Build a cv from a file
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app An Container instance
     */
    public function register(Container $app)
    {
        $app['cv_manager'] = function ($app) {
            $contactManager = new CvManager($app['twig'], $app['markdown']);

            return $contactManager;
        };
    }
}
