<?php

namespace Mmd\Thumbnail\Service\Factory;

use Mmd\Thumbnail\Service\RandomizeNamingStrategy;
use Zend\Math\Rand;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DefaultNamingStrategyFactory
 *
 * @package Mmd\Thumbnail\Service\Factory
 */
class DefaultNamingStrategyFactory implements FactoryInterface
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
        $strategy = new RandomizeNamingStrategy();
        $prefix   = Rand::getString(2, md5(__CLASS__)) . '_';

        $strategy->setPrefix($prefix);

        return $strategy;
    }

}
