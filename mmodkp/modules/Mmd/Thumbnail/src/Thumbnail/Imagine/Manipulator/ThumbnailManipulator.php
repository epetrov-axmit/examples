<?php

namespace Mmd\Thumbnail\Imagine\Manipulator;

use Imagine\Image\ImageInterface;

/**
 * Class ThumbnailManipulator
 *
 * @package Mmd\Thumbnail\Imagine\Manipulator
 */
class ThumbnailManipulator extends BaseManipulator
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

        if ($box->getWidth() > $box->getHeight() && $this->isWider($box)) {

            return $image->resize($box->widen($this->options->getWidth()))->thumbnail($this->createBox());

        } elseif ($box->getWidth() < $box->getHeight() && $this->isHigher($box)) {

            return $image->resize($box->heighten($this->options->getHeight()))->thumbnail($this->createBox());

        }

        if ($this->isWider($image->getSize()) || $this->isHigher($image->getSize())) {
            return $image->resize($this->createBox($this->options->getWidth(), $this->options->getWidth()));
        }

        return $image;
    }

}