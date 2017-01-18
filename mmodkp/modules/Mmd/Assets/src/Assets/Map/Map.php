<?php

namespace Mmd\Assets\Map;
use OutOfBoundsException;

/**
 * Class Map
 *
 * @package Mmd\Assets\Map
 */
class Map
{
    /**
     * Assets sources map
     *
     * @var Bundle[]
     */
    protected $map = [];

    /**
     * Assets version map
     *
     * @var array
     */
    protected $version = [];

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->map);
    }

    /**
     * @param string $name
     *
     * @return Bundle
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new OutOfBoundsException(sprintf('Bundle with name `%s` does not exists', $name));
        }

        return $this->map[$name];
    }

    /**
     * @param Bundle $bundle
     *
     * @return self
     */
    public function add(Bundle $bundle)
    {
        $this->map[$bundle->getName()] = $bundle;

        return $this;
    }

    /**
     * @param string $name
     * @param string $version
     *
     * @return self
     */
    public function addVersion($name, $version)
    {
        $this->version[$name] = (string)$version;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasVersion($name)
    {
        return array_key_exists($name, $this->version);
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getVersion($name)
    {
        if (!$this->hasVersion($name)) {
            return null;
        }

        return $this->version[$name];
    }
}
