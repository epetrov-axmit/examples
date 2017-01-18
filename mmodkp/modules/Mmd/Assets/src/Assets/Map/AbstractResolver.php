<?php

namespace Mmd\Assets\Map;

use InvalidArgumentException;
use Mmd\Assets\Map\Map as MapContainer;
use RuntimeException;
use Zend\Hydrator\HydratorInterface;

/**
 * Class AbstractResolver
 *
 * @package Mmd\Assets\Map
 */
abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var Filter\FilterManager
     */
    protected $filterManager;

    /**
     * AbstractResolver constructor.
     *
     * @param HydratorInterface    $hydrator
     * @param Filter\FilterManager $filterManager
     */
    public function __construct(HydratorInterface $hydrator, Filter\FilterManager $filterManager)
    {
        $this->hydrator      = $hydrator;
        $this->filterManager = $filterManager;
    }

    /**
     * @param string $name
     * @param array  $bundleData
     *
     * @return Bundle
     */
    public function createBundle($name, array $bundleData)
    {
        $bundle = new Bundle($name);

        if (isset($bundleData['files']) && is_array($bundleData['files'])) {
            foreach ($bundleData['files'] as $fileData) {
                $bundle->addFile($this->hydrateFile($fileData));
            }
        }

        return $bundle;
    }

    /**
     * @param array $fileData
     *
     * @return File
     */
    protected function hydrateFile(array $fileData)
    {
        if (empty($fileData['src'])) {
            throw new InvalidArgumentException(
                sprintf('File `src` attribute is not defined in file [%s]', implode(':', $fileData))
            );
        }

        $file = new File($fileData['src']);
        unset($fileData['src']);

        $this->hydrator->hydrate($fileData, $file);

        if (isset($fileData['filters']) && is_array($fileData['filters'])) {
            $this->injectFilters($file, $fileData['filters']);
        }

        return $file;
    }

    /**
     * @param File  $asset
     * @param array $filters
     */
    protected function injectFilters(File $asset, array $filters = [])
    {
        foreach ($filters as $filterSpec) {
            if (empty($filterSpec['name'])) {
                throw new RuntimeException(
                    sprintf('Filter must have `name` key, defined in asset `%s`', $asset->getSrc())
                );
            }

            $name    = $filterSpec['name'];
            $options = [];

            unset($filterSpec['name']);

            if (!empty($filterSpec)) {
                $options = $filterSpec;
            }

            if (!$this->filterManager->has($name)) {
                throw new RuntimeException(
                    sprintf('Filter `%s` is not supported, called in `%s`', $name, $asset->getSrc())
                );
            }

            $asset->attachFilter($this->filterManager->get($name, $options));
        }
    }

    /**
     * @param MapContainer $map
     * @param string       $bundleName
     * @param array        $bundleData
     */
    public function nestBundles(MapContainer $map, $bundleName, array $bundleData)
    {
        $bundle = $map->has($bundleName) ? $map->get($bundleName) : null;

        if (null === $bundle) {
            throw new InvalidArgumentException(
                sprintf('Bundle with name `%s` does not exist in the map', $bundleName)
            );
        }

        if (!isset($bundleData['bundles']) || !is_array($bundleData['bundles'])) {
            return;
        }

        foreach ($bundleData['bundles'] as $name) {
            if (!$map->has($name)) {
                throw new RuntimeException(
                    sprintf('Can\'t use bundle with name `%s`, is not defined in the Map', $name)
                );
            }

            $bundle->addBundle($map->get($name));
        }
    }
}
