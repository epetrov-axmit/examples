<?php

namespace Mmd\Thumbnail\Service\Factory;

use Application\Filesystem\FlysystemFactory;
use Mmd\Thumbnail\Service\Image as ImageService;
use RuntimeException;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\SeparatorToCamelCase;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ImageServiceAbstractFactory
 *
 * @package Mmd\Thumbnail\Service\Factory
 */
class ImageServiceAbstractFactory implements AbstractFactoryInterface
{

    const PREFIX = 'mmd.imageService';

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var FlysystemFactory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $defined = [];

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (!$this->isMatchedRequest($requestedName)) {
            return false;
        }

        if (!$filesystemConfig = $this->defineFilesystem($requestedName)) {
            return false;
        }

        $this->defined[$name] = $this->getMethodName($filesystemConfig);

        return true;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $this->serviceLocator = $serviceLocator;
        $this->factory        = $serviceLocator->get('mmd.flysystemFactory');

        $imagine    = $serviceLocator->get('mmd.imagine.gd');
        $filesystem = $this->{$this->defined[$name]}();

        $service = new ImageService($imagine, $filesystem);
        $service->setNamingStrategy($serviceLocator->get('mmd.thumbnail.defaultNamingStrategy'));

        return $service;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    protected function getThumbnailsFilesystem()
    {
        return $this->factory->create($this->ensureConfig('thumbnails'));
    }

    protected function getOriginalImagesFilesystem()
    {
        return $this->factory->create($this->ensureConfig('original_images'));
    }

    /**
     * @param $namespace
     *
     * @return array|object
     */
    protected function ensureConfig($namespace)
    {
        $config = $this->serviceLocator->get('mmd-assets-config');
        $config = isset($config[$namespace]['storage']) ? $config[$namespace]['storage'] : [];

        if (empty($config)) {
            $storagePath = 'mmd-assets -> ' . $namespace . ' -> storage';
            throw new RuntimeException(
                sprintf(
                    '%s storage config is not found. Must be defined under [%s] array',
                    ucfirst($namespace), $storagePath
                )
            );
        }

        return $config;
    }

    /**
     * @param string $requestedName
     *
     * @return int
     */
    protected function isMatchedRequest($requestedName)
    {
        return preg_match('@^' . static::PREFIX . '@iu', $requestedName);
    }

    /**
     * @param $requestedName
     *
     * @return string|null
     */
    protected function defineFilesystem($requestedName)
    {
        $parts = explode('.', $requestedName);

        $filter = new FilterChain();
        $filter->attach(new UnderscoreToCamelCase())->attach(new SeparatorToCamelCase('-'));

        $filesystemConfig = $filter->filter(array_pop($parts));

        if (method_exists($this, $this->getMethodName($filesystemConfig))) {
            return $filesystemConfig;
        }

        return null;
    }

    /**
     * @param string $filesystemConfig
     *
     * @return string
     */
    protected function getMethodName($filesystemConfig)
    {
        return 'get' . ucfirst($filesystemConfig) . 'Filesystem';
    }

}
