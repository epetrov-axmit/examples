<?php

namespace Mmd\Assets\Map\Filter\Factory;

use Mmd\Assets\Map\Filter\IeOnlyFilter;
use Zend\Mvc\Application;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IeOnlyFilterFactory
 *
 * @package Mmd\Assets\Map\Filter\Factory
 */
class IeOnlyFilterFactory implements FactoryInterface
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
        /** @var Application $app */
        $app = $serviceLocator->getServiceLocator()->get('Application');

        return new IeOnlyFilter($app->getRequest());
    }
}
