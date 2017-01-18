<?php

namespace Mmd\Thumbnail\Hydrator\Strategy;

use Application\Hydrator\Strategy\StrategyEvent;
use InvalidArgumentException;
use Mmd\Thumbnail\Entity\Image;
use Mmd\Thumbnail\Hydrator\ExtractionScaleProviderInterface;
use Mmd\Thumbnail\Hydrator\RemoveTempSourceProviderInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class ImageStrategy
 *
 * @package Mmd\Thumbnail\Hydrator\Strategy
 */
class ImageStrategy implements StrategyInterface,
                               ExtractionScaleProviderInterface,
                               RemoveTempSourceProviderInterface
{

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var string
     */
    protected $extractionScale;

    /**
     * @var bool
     */
    protected $removeTempSource = true;

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    /**
     * @param EventManagerInterface $eventManager
     *
     * @return self
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers([__CLASS__, get_class($this)]);

        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed  $value  The original value.
     * @param object $object (optional) The original object for context.
     *
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        if (!$value instanceof Image) {
            return $value;
        }

        $event = new StrategyEvent(StrategyEvent::EVENT_EXTRACT, $this);
        $event->setObject($value);

        $this->getEventManager()->trigger($event);

        return $event->getValue();
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array $data  (optional) The original data for context.
     *
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if (!$value || $value instanceof Image) {
            return $value;
        }

        $source = is_string($value) ? $value : null;

        if (!$source && is_array($value) && isset($value['tmp_name'])) {
            $source = $value['tmp_name'];
        }

        if (!$source) {
            throw new InvalidArgumentException(
                sprintf(
                    'Strategy can hydrate only string or FILES array, [%s] given',
                    is_object($value) ? get_class($value) : gettype($value)
                )
            );
        }

        $event = new StrategyEvent(StrategyEvent::EVENT_HYDRATE, $this);
        $event->setValue($source);

        $this->getEventManager()->trigger($event);

        return $event->getObject();
    }

    /**
     * Returns thumbnail scale
     *
     * @return string
     */
    public function getExtractionScale()
    {
        return $this->extractionScale;
    }

    /**
     * @param string $scale
     *
     * @return self
     */
    public function setExtractionScale($scale)
    {
        $this->extractionScale = (string)$scale;
        return $this;
    }

    /**
     * @param boolean $removeTempSource
     *
     * @return self
     */
    public function setRemoveTempSource($removeTempSource = true)
    {
        $this->removeTempSource = (bool)$removeTempSource;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRemoveTempSource()
    {
        return $this->removeTempSource;
    }

}
