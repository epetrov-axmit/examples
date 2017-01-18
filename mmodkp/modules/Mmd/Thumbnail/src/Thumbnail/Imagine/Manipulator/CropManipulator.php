<?php

namespace Mmd\Thumbnail\Imagine\Manipulator;

use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

/**
 * Class CropManipulator
 *
 * @package Mmd\Thumbnail\Imagine\Manipulator
 */
class CropManipulator extends BaseManipulator
{
    /**
     * Processes Imagine ImageInterface object
     *
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    public function processImage(ImageInterface $image)
    {
        $box = $image->getSize();

        if ($box->getWidth() > $box->getHeight()) {
            if ($this->isHigher($box)) {
                $image->resize($image->getSize()->heighten($this->options->getHeight()));
            }

            $delta = floor(($image->getSize()->getWidth() - $this->options->getWidth()) / 2);
            return $image->crop(new Point(max(0, $delta), 0), $this->createBox());

        } elseif ($box->getWidth() < $box->getHeight()) {
            if ($this->isWider($box)) {
                $image->resize($image->getSize()->widen($this->options->getWidth()));
            }

            $delta = floor(($image->getSize()->getHeight() - $this->options->getHeight()) / 2);
            return $image->crop(new Point(0, max(0, $delta)), $this->createBox());
        }

        return $image->thumbnail(
            $this->createBox($this->options->getWidth(), $this->options->getWidth()),
            ImageInterface::THUMBNAIL_OUTBOUND
        );
    }
}