<?php

namespace Mmd\Assets\Console\Factory;

use Interop\Container\ContainerInterface;
use Mmd\Assets\Console\EnsureAssetsCommand;
use RuntimeException;

/**
 * Class EnsureAssetsCommandFactory
 *
 * @package Mmd\Assets\Console
 */
class EnsureAssetsCommandFactory
{

    /**
     * Create service
     *
     * @param ContainerInterface $container
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('Config');

        if (empty($config['app.assets']['map']['file'])) {
            throw new RuntimeException(
                'Assets map file must be defined under `app.assets.map.file` config'
            );
        }

        return new EnsureAssetsCommand($config['app.assets']['map']['file']);
    }
}
