<?php

namespace Mmd\Thumbnail\Hydrator\Strategy\ImageStrategy;

use Application\Hydrator\Strategy\StrategyEvent;
use Epos\UserCore\Service\UserService;
use Mmd\Thumbnail\Entity\Image;
use Mmd\Thumbnail\Entity\Thumbnail;
use Mmd\Thumbnail\Imagine\ManipulatorInterface;
use Mmd\Thumbnail\Service\Image as ImageService;
use Traversable;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 * Class ThumbnailsListener
 *
 * @package Mmd\Thumbnail\Hydrator\Strategy\ImageStrategy
 */
class ThumbnailsListener extends AbstractListenerAggregate
{

    use ArrayOrTraversableGuardTrait;

    /**
     * @var ImageService
     */
    protected $service;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var ManipulatorInterface[]
     */
    protected $manipulators = [];

    /**
     * ThumbnailsListener constructor.
     *
     * @param ImageService $service
     * @param UserService  $userService
     */
    public function __construct(ImageService $service, UserService $userService)
    {
        $this->service     = $service;
        $this->userService = $userService;
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
     * @return ManipulatorInterface[]
     */
    public function getManipulators()
    {
        return $this->manipulators;
    }

    /**
     * @param array|Traversable $manipulators
     *
     * @return self
     */
    public function setManipulators($manipulators)
    {
        $this->guardForArrayOrTraversable($manipulators);

        $this->manipulators = [];

        foreach ($manipulators as $scale => $manipulator) {
            $this->addManipulator($scale, $manipulator);
        }

        return $this;
    }

    /**
     * @param string               $scale
     * @param ManipulatorInterface $manipulator
     *
     * @return self
     */
    public function addManipulator($scale, ManipulatorInterface $manipulator)
    {
        if (array_key_exists($scale, $this->manipulators)) {
            unset($this->manipulators[$scale]);
        }

        $this->manipulators[$scale] = $manipulator;

        return $this;
    }

    /**
     * @param StrategyEvent $event
     *
     * @return Image
     */
    public function onHydrate(StrategyEvent $event)
    {
        $value = $event->getValue();

        if (!$value || !is_string($value)) {
            return $value;
        }

        $object = $event->getObject();
        if (!$object instanceof Image) {
            $object = new Image();
        }

        foreach ($this->manipulators as $scale => $manipulator) {
            $thumb = new Thumbnail($scale);
            $thumb->setSource($this->service->save($value, $manipulator));
            $object->addThumbnail($thumb);

            if ($this->userService->hasAuthenticatedUser()) {
                $thumb->setCreatedBy($this->userService->getAuthenticatedUser());
            }
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
