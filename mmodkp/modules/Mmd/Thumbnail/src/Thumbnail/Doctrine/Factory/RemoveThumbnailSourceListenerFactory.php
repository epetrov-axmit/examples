<?php
/**
 * Created by PhpStorm.
 * User: ed
 * Date: 25.03.2015
 * Time: 0:45
 */

namespace Mmd\Thumbnail\Doctrine\Factory;


use League\Flysystem\FilesystemInterface;
use Mmd\Thumbnail\Doctrine\BaseRemoveSourceListener;
use Mmd\Thumbnail\Doctrine\RemoveThumbnailSourceListener;
use RuntimeException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RemoveThumbnailSourceListenerFactory
 *
 * @package Mmd\Thumbnail\Doctrine\Factory
 */
class RemoveThumbnailSourceListenerFactory extends BaseRemoveSourceListenerFactory
{
    /**
     * @param ServiceLocatorInterface $sl
     *
     * @return FilesystemInterface
     */
    protected function createFilesystem(ServiceLocatorInterface $sl)
    {
        $config = $sl->get('mmd-assets-config');
        $config = isset($config['thumbnails']['storage']) ? $config['thumbnails']['storage'] : [];

        if (empty($config)) {
            $storagePath = 'mmd-assets -> thumbnails -> storage';
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
        return new RemoveThumbnailSourceListener($filesystem);
    }

}