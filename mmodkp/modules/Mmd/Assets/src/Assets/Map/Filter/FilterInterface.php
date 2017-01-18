<?php

namespace Mmd\Assets\Map\Filter;

use Mmd\Assets\Map\File;

/**
 * Interface FilterInterface
 *
 * @package Mmd\Assets\Map\Filter
 */
interface FilterInterface
{
    /**
     * Checks if current filter is satisfies for current conditions
     *
     * @param File $asset
     *
     * @return bool
     */
    public function isSatisfy(File $asset);
}
