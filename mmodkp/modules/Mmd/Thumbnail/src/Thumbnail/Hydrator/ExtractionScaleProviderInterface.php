<?php

namespace Mmd\Thumbnail\Hydrator;

/**
 * Interface ExtractionScaleProviderInterface
 *
 * @package Mmd\Thumbnail\Hydrator
 */
interface ExtractionScaleProviderInterface
{

    /**
     * Returns thumbnail scale
     *
     * @return string
     */
    public function getExtractionScale();

}