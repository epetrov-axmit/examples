<?php

namespace Mmd\Assets\Map;

use InvalidArgumentException;
use Mmd\Assets\Map\Map as MapContainer;
use Zend\Json\Json;

/**
 * Class JsonResolver
 *
 * @package Mmd\Assets\Map
 */
class JsonResolver extends AbstractResolver
{

    /**
     * @param string       $mapFile
     * @param MapContainer $map
     *
     * @return void
     */
    public function populate($mapFile, MapContainer $map)
    {
        if (!is_readable($mapFile)) {
            throw new InvalidArgumentException(
                sprintf('Provided map file [%s] is not readable', $mapFile)
            );
        }

        $mapArray = Json::decode(file_get_contents($mapFile), Json::TYPE_ARRAY);

        $assetMap = isset($mapArray['map']) && is_array($mapArray['map']) ? $mapArray['map'] : $mapArray;

        array_walk(
            $assetMap,
            function ($data, $name) use ($map) {
                $map->add($this->createBundle($name, $data));
            }
        );

        array_walk(
            $assetMap,
            function ($data, $name) use ($map) {
                $this->nestBundles($map, $name, $data);
            }
        );

        $versionMap = isset($mapArray['version']) && is_array($mapArray['version']) ? $mapArray['version'] : [];

        array_walk(
            $versionMap,
            function ($version, $name) use ($map) {
                $map->addVersion($name, $version);
            }
        );
    }
}
