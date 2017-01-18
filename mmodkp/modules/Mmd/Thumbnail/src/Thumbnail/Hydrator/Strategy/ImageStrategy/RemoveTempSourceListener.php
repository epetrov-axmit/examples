<?php

namespace Mmd\Thumbnail\Hydrator\Strategy\ImageStrategy;

use Application\Hydrator\Strategy\StrategyEvent;
use Mmd\Thumbnail\Hydrator\RemoveTempSourceProviderInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

/**
 * Class RemoveTempSourceListener
 *
 * @package Mmd\Thumbnail\Hydrator\Strategy\ImageStrategy
 */
class RemoveTempSourceListener extends AbstractListenerAggregate
{

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @param int                   $priority
     */
    public function attach(EventManagerInterface $events, $priority = -1000)
    {
        $this->detach($events);

        $this->listeners[] = $events->attach(StrategyEvent::EVENT_HYDRATE, [$this, 'onHydrate'], $priority);
    }

    /**
     * @param StrategyEvent $event
     *
     * @return object
     */
    public function onHydrate(StrategyEvent $event)
    {
        $target = $event->getTarget();
        if ($target instanceof RemoveTempSourceProviderInterface && !$target->isRemoveTempSource()) {
            return $event->getObject();
        }

        $value = $event->getValue();
        if (!$value || !is_string($value)) {
            return $event->getObject();
        }

        if(file_exists($value)) {
            unlink($value);
        }

        return $event->getObject();
    }

}