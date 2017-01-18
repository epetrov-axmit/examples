<?php

namespace Mmd\Thumbnail\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use League\Flysystem\FilesystemInterface;

/**
 * Class BaseRemoveSourceListener
 *
 * @package Mmd\Thumbnail\Doctrine
 */
abstract class BaseRemoveSourceListener implements EventSubscriber
{

    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postRemove
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @return mixed
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        return $this->doPostRemove($args);
    }

    /**
     * Performs the actual action on postRemove
     *
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    abstract protected function doPostRemove(LifecycleEventArgs $args);

}