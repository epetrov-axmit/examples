<?php

namespace Mmd\Thumbnail\Imagine;

use Imagine\Image\ImageInterface;
use Mmd\Thumbnail\Options\ImageOptions;

/**
 * Interface ManipulatorInterface
 *
 * @package Mmd\Thumbnail\Imagine
 */
interface ManipulatorInterface
{

    /**
     * Processes Imagine ImageInterface object
     *
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    public function processImage(ImageInterface $image);

    /**
     * @return ImageOptions
     */
    public function getOptions();
}