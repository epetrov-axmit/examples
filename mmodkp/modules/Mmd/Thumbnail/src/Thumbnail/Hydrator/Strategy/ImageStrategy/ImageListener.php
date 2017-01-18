<?php

namespace Mmd\Thumbnail\Hydrator\Strategy\ImageStrategy;

use Application\Hydrator\Strategy\StrategyEvent;
use Epos\UserCore\Service\UserService;
use Mmd\Thumbnail\Entity\Image;
use Mmd\Thumbnail\Imagine\ManipulatorInterface;
use Mmd\Thumbnail\Service\Image as ImageService;
use RuntimeException;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

/**
 * Class ImageListener
 *
 * @package Mmd\Thumbnail\Hydrator\Aggregate
 */
class ImageListener extends AbstractListenerAggregate
{

    /**
     * @var ImageService
     */
    protected $service;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var ManipulatorInterface
     */
    protected $manipulator;

    /**
     * ImageListener constructor.
     *
     * @param ImageService         $service
     * @param UserService          $userService
     * @param ManipulatorInterface $manipulator
     */
    public function __construct(ImageService $service, UserService $userService, ManipulatorInterface $manipulator)
    {
        $this->service     = $service;
        $this->userService = $userService;
        $this->manipulator = $manipulator;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->detach($events);

        $this->listeners[] = $events->attach(StrategyEvent::EVENT_HYDRATE, [$this, 'onHydrate']);
    }

    /**
     * Hydrates Image object to hydration object
     *
     * @param StrategyEvent $event
     *
     * @return object
     */
    public function onHydrate(StrategyEvent $event)
    {
        $value = $event->getValue();

        if (!$value || !is_string($value)) {
            return $value;
        }

        $newPath = $this->service->save($value, $this->manipulator);
        if (!$newPath) {
            throw new RuntimeException(
                sprintf('Could not save image [%s]', $value)
            );
        }

        $object = new Image();
        $object->setOriginalSource($newPath);

        if ($this->userService->hasAuthenticatedUser()) {
            $object->setCreatedBy($this->userService->getAuthenticatedUser());
        }

        $event->setObject($object);

        return $object;
    }

    /**
     * @return ImageService
     */
    public function getService()
    {
        return $this->service;
    }

}
