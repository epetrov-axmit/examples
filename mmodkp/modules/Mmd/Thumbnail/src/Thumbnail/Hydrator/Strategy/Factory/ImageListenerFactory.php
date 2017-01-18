<?php

namespace Mmd\Thumbnail\Hydrator\Strategy\Factory;

use Epos\UserCore\Service\UserService;
use Mmd\Thumbnail\Hydrator\Strategy\ImageStrategy\ImageListener;
use Mmd\Thumbnail\Imagine\Manipulator\ThumbnailManipulator;
use Mmd\Thumbnail\Service\Factory\ImageServiceAbstractFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ImageListenerFactory
 *
 * @package Mmd\Thumbnail\Hydrator\Strategy\Factory
 */
class ImageListenerFactory implements FactoryInterface
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
        $imageService = $serviceLocator->get(ImageServiceAbstractFactory::PREFIX . '.original_images');
        $options      = $serviceLocator->get('mmd.thumbnail.options.originalImage');
        $userService  = $serviceLocator->get(UserService::class);

        return new ImageListener($imageService, $userService, new ThumbnailManipulator($options));
    }

}
