<?php

namespace Mmd\Thumbnail\Service;

class RandomizeNamingStrategy implements NamingStrategyInterface
{

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return self
     */
    public function setPrefix($prefix)
    {
        $this->prefix = (string)$prefix;
        return $this;
    }

    /**
     * Normalizes name due to specific rules
     *
     * @param string $name
     *
     * @return string
     */
    public function normalize($name)
    {
        $ext = '';

        if(false !== strpos($name, '.')) {
            $parts = explode('.', $name);
            $ext = '.' . array_pop($parts);
        }

        $prefix = empty($this->prefix) ? '' : $this->prefix;

        return uniqid($prefix) . $ext;
    }

}