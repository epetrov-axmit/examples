<?php

namespace Mmd\Assets\View\Helper;

use InvalidArgumentException;
use Mmd\Assets\Map;
use RuntimeException;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadLink;
use Zend\View\Helper\HeadScript;
use Zend\View\Helper\InlineScript;

/**
 * Class AssetResolver
 *
 * @package Mmd\Assets\View\Helper
 */
class AssetResolver extends AbstractHelper
{
    /**
     * @var Map\Map
     */
    protected $map;

    /**
     * @var HeadScript
     */
    protected $headScript;

    /**
     * @var HeadLink
     */
    protected $headLink;

    /**
     * @var InlineScript
     */
    protected $inlineScript;

    /**
     * @var array
     */
    protected $appended = [];

    /**
     * AssetResolver constructor.
     *
     * @param Map\Map $map
     */
    public function __construct(Map\Map $map)
    {
        $this->map = $map;
    }

    /**
     * @return self
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function append($name)
    {
        $files = $this->getAssets($name);

        if (empty($files)) {
            return $this;
        }

        foreach ($files as $file) {
            $this->doAppend($file, $file->getAppend(), $this->findVersion($name));
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Map\File[]
     */
    private function getAssets($name)
    {
        if (!$this->map->has($name)) {
            throw new InvalidArgumentException(
                sprintf('Asset with name `%s` does not exist on the map', $name)
            );
        }

        $bundle = $this->map->get($name);
        $files  = $bundle->flattenFiles();

        return empty($files) ? [] : $files;
    }

    /**
     * @param Map\File $asset
     * @param string   $method
     * @param null     $version
     */
    private function doAppend(Map\File $asset, $method = Map\File::APPEND_HEAD, $version = null)
    {
        $this->initHelpers();

        if ($this->isAppended($asset)) {
            return;
        }

        if (!$asset->isMatch()) {
            return;
        }

        $ext    = $this->extractExtension($asset->getSrc());
        $source = $version ? $asset->getSrc() . '?v=' . $version : $asset->getSrc();
        //$source     = $this->addOpeningSlash($source);
        $attributes = $asset->getAttributes();
        $condition  = $asset->getCondition() ?: '';

        if ($ext === 'js') {
            if (!empty($condition)) {
                $attributes['conditional'] = $condition;
            }

            switch ($method) {
                case Map\File::APPEND_HEAD:
                    $this->headScript->appendFile($source, 'text/javascript', $attributes);
                    break;
                case Map\File::APPEND_INLINE:
                default:
                    $this->inlineScript->appendFile($source, 'text/javascript', $attributes);
                    break;
            }

            $this->markAsAppended($asset);

            return;
        }

        if ($ext === 'css') {
            $this->headLink->appendStylesheet($source, 'screen', $condition, $attributes);

            $this->markAsAppended($asset);

            return;
        }

        throw new RuntimeException(
            sprintf('Only `.js` and `.css` files are supported for appending, `%s` given', $ext)
        );
    }

    /**
     * Helpers initialize
     *
     * @return void
     */
    private function initHelpers()
    {
        if (!$this->headScript) {
            $this->headScript = $this->getView()->headScript();
        }

        if (!$this->inlineScript) {
            $this->inlineScript = $this->getView()->inlineScript();
        }

        if (!$this->headLink) {
            $this->headLink = $this->getView()->headLink();
        }
    }

    /**
     * @param Map\File $asset
     *
     * @return bool
     */
    private function isAppended(Map\File $asset)
    {
        return isset($this->appended[$asset->getSrc()]);
    }

    /**
     * @param string $source
     *
     * @return string
     */
    private function extractExtension($source)
    {
        $urlParts = parse_url($source);

        return strtolower(pathinfo($urlParts['path'], PATHINFO_EXTENSION));
    }

    /**
     * @param Map\File $asset
     *
     * @return bool
     */
    private function markAsAppended(Map\File $asset)
    {
        return $this->appended[$asset->getSrc()] = true;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    private function findVersion($name)
    {
        return $this->map->hasVersion($name) ? $this->map->getVersion($name) : null;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function appendHead($name)
    {
        $files = $this->getAssets($name);

        if (empty($files)) {
            return $this;
        }

        foreach ($files as $file) {
            $this->doAppend($file, Map\File::APPEND_HEAD, $this->findVersion($name));
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function appendInline($name)
    {
        $files = $this->getAssets($name);

        if (empty($files)) {
            return $this;
        }

        foreach ($files as $file) {
            $this->doAppend($file, Map\File::APPEND_INLINE, $this->findVersion($name));
        }

        return $this;
    }

    /**
     * @param string $src
     *
     * @return string
     */
    private function addOpeningSlash($src)
    {
        return '/' . ltrim($src, '/');
    }
}
