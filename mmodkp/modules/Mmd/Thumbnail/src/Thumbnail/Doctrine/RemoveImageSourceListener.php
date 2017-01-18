<?php

namespace Mmd\Thumbnail\Doctrine;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Mmd\Thumbnail\Entity\Image;

/**
 * Class RemoveImageSourceListener
 *
 * @package Mmd\Thumbnail\Doctrine
 */
class RemoveImageSourceListener extends BaseRemoveSourceListener
{

    /**
     * @var array
     */
    protected $affected = [];

    /**
     * Performs the actual action on postRemove
     *
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    protected function doPostRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Image) {
            return;
        }

        if (in_array($entity->getOriginalSource(), $this->affected)) {
            return;
        }

        $this->filesystem->delete($this->filesystem->removeFullPath($entity->getOriginalSource()));

        array_push($this->affected, $entity->getOriginalSource());
    }

}