<?php

namespace Mmd\Thumbnail\Doctrine;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Mmd\Thumbnail\Entity\Thumbnail;

/**
 * Class RemoveThumbnailSourceListener
 *
 * @package Mmd\Thumbnail\Doctrine
 */
class RemoveThumbnailSourceListener extends BaseRemoveSourceListener
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

        if (!$entity instanceof Thumbnail) {
            return;
        }

        if (in_array($entity->getSource(), $this->affected)) {
            return;
        }

        $this->filesystem->delete($this->filesystem->removeFullPath($entity->getSource()));
        array_push($this->affected, $entity->getSource());
    }

}