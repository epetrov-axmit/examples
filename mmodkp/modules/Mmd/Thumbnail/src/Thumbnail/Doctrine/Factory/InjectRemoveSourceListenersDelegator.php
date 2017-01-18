<?php

namespace Mmd\Thumbnail\Doctrine\Factory;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class InjectRemoveSourceListenersDelegator
 *
 * @package Mmd\Thumbnail\Doctrine\Factory
 */
class InjectRemoveSourceListenersDelegator implements DelegatorFactoryInterface
{
    /**
     * A factory that creates delegates of a given service
     *
     * @param ServiceLocatorInterface $serviceLocator the service locator which requested the service
     * @param string                  $name           the normalized service name
     * @param string                  $requestedName  the requested service name
     * @param callable                $callback       the callback that is responsible for creating the service
     *
     * @return mixed
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        $entityManager = call_user_func($callback);

        $this->injectListeners($entityManager, $serviceLocator);

        return $entityManager;
    }

    /**
     * Injects listeners
     *
     * @param EntityManager           $entityManager
     * @param ServiceLocatorInterface $sl
     */
    protected function injectListeners(EntityManager $entityManager, ServiceLocatorInterface $sl)
    {
        $entityManager->getEventManager()
                      ->addEventSubscriber($sl->get('mmd.thumbnail.doctrine.listener.removeImageSource'));
        $entityManager->getEventManager()
                      ->addEventSubscriber($sl->get('mmd.thumbnail.doctrine.listener.removeThumbnailSource'));
    }

}