<?php

namespace Provider;

use Manager\CourseManager;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Contact manager component Provider.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class CourseManagerServiceProvider implements ServiceProviderInterface
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
        $app['course_manager'] = function ($app) {
            $contactManager = new CourseManager();

            return $contactManager;
        };
    }
}
