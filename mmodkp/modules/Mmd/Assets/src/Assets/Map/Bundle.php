<?php

namespace Mmd\Assets\Map;

use InvalidArgumentException;
use Traversable;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 * Class Bundle
 *
 * @package Mmd\Assets\Map
 */
class Bundle
{

    use ArrayOrTraversableGuardTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var File[]
     */
    protected $files = [];

    /**
     * @var Bundle[]
     */
    protected $bundles = [];

    /**
     * Bundle constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return Bundle[]
     */
    public function getBundles()
    {
        return $this->bundles;
    }

    /**
     * @param File $file
     *
     * @return self
     */
    public function addFile(File $file)
    {
        $found = null;

        foreach ($this->files as $idx => $existingFile) {
            if ($existingFile->getSrc() !== $file->getSrc()) {
                continue;
            }

            $found = $idx;
            break;
        }

        if (null !== $found) {
            $this->files[$found] = $file;
        } else {
            array_push($this->files, $file);
        }

        return $this;
    }

    /**
     * @param array|Traversable $files
     *
     * @return self
     */
    public function setFiles($files)
    {
        $this->guardForArrayOrTraversable($files);

        if ($files instanceof Traversable) {
            $files = iterator_to_array($files);
        }

        $this->files = [];

        foreach ($files as $file) {
            $this->addFile($file);
        }

        return $this;
    }

    /**
     * @param Bundle $bundle
     *
     * @return self
     */
    public function addBundle(Bundle $bundle)
    {
        foreach ($this->bundles as $existingBundle) {
            if ($existingBundle->getName() === $bundle->getName()) {
                throw new InvalidArgumentException(
                    sprintf('Bundle `%s` already exists within bundle `%s`', $bundle->getName(), $this->getName())
                );
            }
        }

        array_push($this->bundles, $bundle);

        return $this;
    }

    /**
     * @param array|Traversable $bundles
     *
     * @return self
     */
    public function setBundles($bundles)
    {
        $this->guardForArrayOrTraversable($bundles);

        if ($bundles instanceof Traversable) {
            $bundles = iterator_to_array($bundles);
        }

        foreach ($bundles as $bundle) {
            $this->addBundle($bundle);
        }

        return $this;
    }

    /**
     * Flattens bundle (and dependent bundles) files
     *
     * @return File[]
     */
    public function flattenFiles()
    {
        $files = [];

        if (!empty($this->bundles)) {
            reset($this->bundles);
            foreach ($this->bundles as $bundle) {
                $files = array_merge($files, $bundle->flattenFiles());
            }
        }

        if (!empty($this->files)) {
            reset($this->files);
            $files = array_merge($files, $this->files);
        }

        return $files;
    }
}
