<?php

namespace Mmd\Thumbnail\Doctrine\Factory;

use Application\Filesystem\FlysystemFactory;
use League\Flysystem\FilesystemInterface;
use Mmd\Thumbnail\Doctrine\BaseRemoveSourceListener;
use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BaseRemoveSourceListenerFactory
 *
 * @package Mmd\Thumbnail\Doctrine\Factory
 */
abstract class BaseRemoveSourceListenerFactory implements FactoryInterface
{

    /**
     * @var FlysystemFactory
     */
    protected $flysystemFactory;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->flysystemFactory = $serviceLocator->get('mmd.flysystemFactory');

        $filesystem = $this->createFilesystem($serviceLocator);

        if (!$filesystem instanceof FilesystemInterface) {
            throw new RuntimeException(
                sprintf(
                    'Method [%s] must return instance of [%s], got [%s]',
                    __CLASS__ . '::createFilesystem()',
                    'League\Flysystem\FilesystemInterface',
                    is_object($filesystem) ? get_class($filesystem) : gettype($filesystem)
                )
            );
        }

        $listener = $this->createListener($filesystem, $serviceLocator);

        if (!$listener instanceof BaseRemoveSourceListener) {
            throw new RuntimeException(
                sprintf(
                    'Method [%s] must return instance of [%s], got [%s]',
                    __CLASS__ . '::createListener()',
                    'Mmd\Thumbnail\Doctrine\BaseRemoveSourceListener',
                    is_object($listener) ? get_class($listener) : gettype($listener)
                )
            );
        }

        return $listener;
    }

    /**
     * @param ServiceLocatorInterface $sl
     *
     * @return FilesystemInterface
     */
    abstract protected function createFilesystem(ServiceLocatorInterface $sl);

    /**
     * @param FilesystemInterface     $filesystem
     * @param ServiceLocatorInterface $sl
     *
     * @return BaseRemoveSourceListener
     */
    abstract protected function createListener(FilesystemInterface $filesystem, ServiceLocatorInterface $sl);

}