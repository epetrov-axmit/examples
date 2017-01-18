<?php

namespace Mmd\Thumbnail\Options\Factory;

use Mmd\Thumbnail\Options\ImageOptions;
use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OriginalImageOptionsFactory
 *
 * @package Mmd\Thumbnail\Options\Factory
 */
class OriginalImageOptionsFactory implements FactoryInterface
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
        $config = $serviceLocator->get('mmd-assets-config');
        if(!isset($config['original_images']) || !is_array($config['original_images'])) {
            throw new RuntimeException(
                sprintf(
                    'Original images config is not defined. Must be an array under %s',
                    'mmd_assets -> original_images'
                )
            );
        }

        $config = $config['original_images'];

        if(!isset($config['width'])) {
            throw new RuntimeException(
                sprintf('Width is not defined under %s', 'mmd-assets -> original_images -> width')
            );
        }

        $options = new ImageOptions();
        $options->setWidth($config['width']);

        return $options;
    }

}