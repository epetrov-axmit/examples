<?php

namespace Mmd\Assets\Map;

use Mmd\Assets\Map\Filter\FilterInterface;

/**
 * Class File
 *
 * @package Mmd\Assets\Map
 */
class File
{

    const APPEND_HEAD   = 'head';
    const APPEND_INLINE = 'inline';

    /**
     * @var string
     */
    protected $src;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var string
     */
    protected $condition = null;

    /**
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * @var string
     */
    protected $append = self::APPEND_INLINE;

    /**
     * File constructor.
     *
     * @param string $src
     */
    public function __construct($src)
    {
        $this->src = $src;
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     *
     * @return self
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppend()
    {
        return $this->append;
    }

    /**
     * @param string $append
     *
     * @return self
     */
    public function setAppend($append)
    {
        $this->append = $append === static::APPEND_HEAD ? static::APPEND_HEAD : static::APPEND_INLINE;

        return $this;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return self
     */
    public function attachFilter(FilterInterface $filter)
    {
        $this->filters[spl_object_hash($filter)] = $filter;

        return $this;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return self
     */
    public function detachFilter(FilterInterface $filter)
    {
        $hash = spl_object_hash($filter);

        if (array_key_exists($hash, $this->filters)) {
            unset($this->filters[$hash]);
        }

        return $this;
    }

    /**
     * Returns true if current asset is match for current request
     *
     * @return bool
     */
    public function isMatch()
    {
        foreach ($this->filters as $filter) {
            if (!$filter->isSatisfy($this)) {
                return false;
            }
        }

        return true;
    }
}
