<?php
/**
 * File contains class GravatarMiddlewareFactory
 */

namespace App\Middleware\Factory;

use App\Middleware\GravatarMiddleware;
use App\Service\Gravatar\GravatarService;
use Interop\Container\ContainerInterface;

/**
 * Class GravatarMiddlewareFactory
 *
 * @package App\Middleware\Factory
 */
class GravatarMiddlewareFactory
{
    /**
     * Gravatar Middleware factory
     *
     * @param ContainerInterface $container
     *
     * @return GravatarMiddleware
     */
    public function __invoke(ContainerInterface $container)
    {
        $service = $container->get(GravatarService::class);

        return new GravatarMiddleware($service);
    }
}
