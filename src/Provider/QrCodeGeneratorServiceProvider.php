<?php

namespace App\Provider;

use Generator\QrCodeGenerator;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Qr code generator component Provider.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class QrCodeGeneratorServiceProvider implements ServiceProviderInterface
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
        $app['qr_code_generator'] = function ($app) {
            return new QrCodeGenerator($app['url_generator']);
        };
    }
}
