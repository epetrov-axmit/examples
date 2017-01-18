<?php

namespace Mmd\Assets\Map;

use Mmd\Assets\Map\Map as MapContainer;

/**
 * Interface ResolverInterface
 *
 * @package Mmd\Assets\Map
 */
interface ResolverInterface
{
    /**
     * @param string $mapFile
     * @param MapContainer    $map
     *
     * @return void
     */
    public function populate($mapFile, MapContainer $map);
}
