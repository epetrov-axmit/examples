<?php
/**
 * File contains class GravatarServiceFactory
 */

namespace App\Service\Factory\Gravatar;

use App\Service\Gravatar\GravatarService;
use App\Service\Gravatar\IsReachableValidator;
use Interop\Container\ContainerInterface;
use RuntimeException;

/**
 * Class GravatarServiceFactory
 *
 * @package App\Service\Factory\Gravatar
 */
class GravatarServiceFactory
{
    /**
     * Gravatar Service factory
     *
     * @param ContainerInterface $container
     *
     * @return GravatarService
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        if (!isset($config['gravatar']['endpoint'])) {
            throw new RuntimeException(
                'Gravatar endpoint must be specified under `gravatar.endpoint` config'
            );
        }

        $endpoint  = $config['gravatar']['endpoint'];
        $validator = $container->get(IsReachableValidator::class);

        return new GravatarService($endpoint, $validator);
    }
}
