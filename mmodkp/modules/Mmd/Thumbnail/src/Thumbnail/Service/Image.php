<?php

namespace Mmd\Thumbnail\Service;

use Imagine\Image\BoxInterface;
use Imagine\Image\ImagineInterface;
use League\Flysystem\Filesystem;
use Mmd\Thumbnail\Imagine\ManipulatorInterface;
use Mmd\Thumbnail\Options\ImageOptions;
use RuntimeException;

class Image
{

    const DEFAULT_FORMAT = 'jpg';

    /**
     * @var ImagineInterface
     */
    protected $imagine;

    /**
     * @var Filesystem
     */
    protected $saveHandler;

    /**
     * @var string
     */
    protected $boxClass = 'Imagine\\Image\\Box';

    /**
     * @var NamingStrategyInterface
     */
    protected $namingStrategy;

    public function __construct(ImagineInterface $imagine, Filesystem $saveHandler)
    {
        $this->imagine     = $imagine;
        $this->saveHandler = $saveHandler;
    }

    /**
     * @param NamingStrategyInterface $namingStrategy
     *
     * @return self
     */
    public function setNamingStrategy($namingStrategy)
    {
        $this->namingStrategy = $namingStrategy;
        return $this;
    }

    /**
     * @param string               $source
     * @param ManipulatorInterface $manipulator
     *
     * @return string
     *
     */
    public function save($source, ManipulatorInterface $manipulator)
    {
        $format = $manipulator->getOptions()->getFormat() ?: static::DEFAULT_FORMAT;

        $img = $manipulator->processImage($this->imagine->open($source))->get($format);

        $nameParts = explode('.', $this->normalizeName($source, $manipulator->getOptions()));
        array_pop($nameParts);
        array_push($nameParts, $format);

        $name = implode('.', $nameParts);

        $this->saveHandler->put($name, $img);
        $newPath = $this->saveHandler->getMetadata($name)['path'];

        return $this->saveHandler->getFullPath($newPath);
    }

    /**
     * Internal filename normalization
     *
     * @param string       $name
     *
     * @param ImageOptions $options
     *
     * @return string
     */
    protected function normalizeName($name, ImageOptions $options)
    {
        $subDir         = substr(md5(date('m')), 0, 2) . '/' . substr(md5(date('d')), 0, 2);
        $normalizedName = $this->namingStrategy ? $this->namingStrategy->normalize($name) : basename($name);
        $normalizedName = $subDir . '/' . ltrim($normalizedName, '/\\');

        if ($options->getDir()) {
            $normalizedName = rtrim($options->getDir(), '/\\') . '/' . $normalizedName;
        }

        return $normalizedName;
    }

    /**
     * @param $width
     * @param $height
     *
     * @return BoxInterface
     */
    protected function createBox($width, $height)
    {
        $boxClass = $this->boxClass;

        /** @var BoxInterface $box */
        $box = new $boxClass($width, $height);

        if (!$box instanceof BoxInterface) {
            throw new RuntimeException(
                sprintf(
                    'Instance of [%s] expected, got [%s]',
                    'Imagine\Image\BoxInterface',
                    get_class($box)
                )
            );
        }

        return $box;
    }

}