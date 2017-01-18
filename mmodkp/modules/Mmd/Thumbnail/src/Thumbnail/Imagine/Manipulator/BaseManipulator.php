<?php

namespace Mmd\Thumbnail\Imagine\Manipulator;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Mmd\Thumbnail\Imagine\ManipulatorInterface;
use Mmd\Thumbnail\Options\ImageOptions;

/**
 * Class BaseManipulator
 *
 * @package Mmd\Thumbnail\Imagine\Manipulator
 */
abstract class BaseManipulator implements ManipulatorInterface
{

    /**
     * @var ImageOptions
     */
    protected $options;

    /**
     * @param ImageOptions $options
     */
    public function __construct(ImageOptions $options)
    {
        if($options->getHeight() <= 0 && $options->getWidth() > 0) {
            $options->setHeight($options->getWidth());
        }

        $this->options = $options;
    }

    /**
     * @return ImageOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Checks if given BoxInterface object width is more than current options width
     *
     * @param BoxInterface $box
     *
     * @return bool
     */
    protected function isWider(BoxInterface $box)
    {
        return $box->getWidth() > $this->options->getWidth();
    }

    /**
     * Checks if given BoxInterface object height is more than current options height
     *
     * @param BoxInterface $box
     *
     * @return bool
     */
    protected function isHigher(BoxInterface $box)
    {
        return $box->getHeight() > $this->options->getHeight();
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return Box
     */
    protected function createBox($width = null, $height = null)
    {
        $width  = $width ?: $this->options->getWidth();
        $height = $height ?: $this->options->getHeight();

        return new Box($width, $height);
    }
}