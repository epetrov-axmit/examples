<?php

namespace Mmd\Thumbnail\Hydrator\Strategy\Factory;

use Epos\UserCore\Service\UserService;
use Mmd\Thumbnail\Hydrator\Strategy\ImageStrategy\ThumbnailsListener;
use Mmd\Thumbnail\Service\Factory\ImageServiceAbstractFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ThumbnailsListenerFactory
 *
 * @package Mmd\Thumbnail\Hydrator\Strategy\Factory
 */
class ThumbnailsListenerFactory implements FactoryInterface
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
        $imageService = $serviceLocator->get(ImageServiceAbstractFactory::PREFIX . '.thumbnails');
        $userService  = $serviceLocator->get(UserService::class);
        $listener     = new ThumbnailsListener($imageService, $userService);

        return $listener;
    }

}
