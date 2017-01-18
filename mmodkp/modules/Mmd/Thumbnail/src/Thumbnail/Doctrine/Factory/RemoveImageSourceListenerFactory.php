<?php

namespace Mmd\Thumbnail\Doctrine\Factory;

use League\Flysystem\FilesystemInterface;
use Mmd\Thumbnail\Doctrine\BaseRemoveSourceListener;
use Mmd\Thumbnail\Doctrine\RemoveImageSourceListener;
use RuntimeException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RemoveImageSourceListenerFactory
 *
 * @package Mmd\Thumbnail\Doctrine\Factory
 */
class RemoveImageSourceListenerFactory extends BaseRemoveSourceListenerFactory
{
    /**
     * @param ServiceLocatorInterface $sl
     *
     * @return FilesystemInterface
     */
    protected function createFilesystem(ServiceLocatorInterface $sl)
    {
        $config = $sl->get('mmd-assets-config');
        $config = isset($config['original_images']['storage']) ? $config['original_images']['storage'] : [];

        if (empty($config)) {
            $storagePath = 'mmd-assets -> original_images -> storage';
            throw new RuntimeException(
                sprintf(
                    'storage config is not found. Must be defined under [%s] array', $storagePath
                )
            );
        }

        return $this->flysystemFactory->create($config);
    }

    /**
     * @param FilesystemInterface     $filesystem
     * @param ServiceLocatorInterface $sl
     *
     * @return BaseRemoveSourceListener
     */
    protected function createListener(FilesystemInterface $filesystem, ServiceLocatorInterface $sl)
    {
        return new RemoveImageSourceListener($filesystem);
    }

}