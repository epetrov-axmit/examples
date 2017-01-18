<?php

namespace MmdAdmin\Game;

use Zend\ModuleManager\Feature;

class Module implements
    Feature\ConfigProviderInterface,
    Feature\AutoloaderProviderInterface,
    Feature\ViewHelperProviderInterface
{
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/Game',
                ],
            ],
        ];
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'adminGameEditForm'     => 'MmdAdmin\\Game\\View\\Helper\\Form\\GameEditForm',
                'adminGameFilterForm'   => 'MmdAdmin\\Game\\View\\Helper\\Form\\GameFilterForm',
                'adminServerEditForm'   => 'MmdAdmin\\Game\\View\\Helper\\Form\\ServerEditForm',
                'adminServerFilterForm' => 'MmdAdmin\\Game\\View\\Helper\\Form\\ServerFilterForm',
            ],
        ];
    }


}
