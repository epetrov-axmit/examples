<?php

namespace Mmd\Thumbnail\Hydrator\Strategy\Factory;

use Mmd\Thumbnail\Hydrator\Strategy\ImageStrategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ImageStrategyFactory
 *
 * @package Mmd\Thumbnail\Hydrator\Factory
 */
class ImageStrategyFactory implements FactoryInterface
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
        $strategy = new ImageStrategy();

        $serviceLocator->get('mmd.thumbnail.strategy.listener.image')->attach($strategy->getEventManager());
        $serviceLocator->get('mmd.thumbnail.strategy.listener.removeTemp')->attach($strategy->getEventManager());

        return $strategy;
    }

}