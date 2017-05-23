<?php

namespace Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Endroid\QrCode\QrCode;

/**
 * QR code generation provider.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class QrCodeServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['qrcode.options'] = array();

        $app['qrcode'] = function ($app) {
            $qrCode = new QrCode();

            foreach ($app['qrcode.options'] as $key => $value) {
                $method = 'set'.implode('', array_map('ucwords', explode('_', $key)));

                if (method_exists($qrCode, $method)) {
                    $qrCode->$method($value);
                }
            }

            return $qrCode;
        };
    }
}
