<?php

namespace App\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Knp\Snappy\Image;
use Knp\Snappy\Pdf;

/**
 * Silex service provider to integrate Snappy library.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class SnappyServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        $app['snappy.image'] = function ($app) {
            return new Image(
                isset($app['snappy.image_binary']) ? $app['snappy.image_binary'] : '/usr/local/bin/wkhtmltoimage',
                isset($app['snappy.image_options']) ? $app['snappy.image_options'] : array()
            );
        };

        $app['snappy.pdf'] = function ($app) {
            return new Pdf(
                isset($app['snappy.pdf_binary']) ? $app['snappy.pdf_binary'] : '/usr/local/bin/wkhtmltopdf',
                isset($app['snappy.pdf_options']) ? $app['snappy.pdf_options'] : array()
            );
        };
    }
}
