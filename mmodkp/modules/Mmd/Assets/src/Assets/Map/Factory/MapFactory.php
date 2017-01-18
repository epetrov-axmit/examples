<?php

namespace Mmd\Assets\Map\Factory;

use Mmd\Assets\Map\Filter\FilterManager;
use Mmd\Assets\Map\JsonResolver;
use Mmd\Assets\Map\Map;
use RuntimeException;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MapFactory
 *
 * @package Mmd\Assets\Map\Factory
 */
class MapFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (empty($config['app.assets']['map']['file'])) {
            throw new RuntimeException(
                sprintf('Assets map file is not defined under `app.assets.map.file` config')
            );
        }

        $mapFile = $config['app.assets']['map']['file'];

        $map      = new Map();
        $resolver = new JsonResolver(new ClassMethods(), $serviceLocator->get(FilterManager::class));
        $resolver->populate($mapFile, $map);

        return $map;
    }
}
