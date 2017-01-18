<?php

namespace Mmd\Thumbnail\View\Helper;

use Mmd\Thumbnail\Entity\Enum\ScaleEnum;
use Mmd\Thumbnail\Entity\Image;
use Mmd\Thumbnail\Entity\Thumbnail;
use Mmd\Thumbnail\Helper\SourceRendererInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ThumbnailHelper
 *
 * @package Mmd\Thumbnail\View\Helper
 */
class ThumbnailHelper extends AbstractHelper
{

    /**
     * @var SourceRendererInterface
     */
    protected $renderer;

    /**
     * @var Image
     */
    protected $image;

    /**
     * ThumbnailHelper constructor.
     *
     * @param SourceRendererInterface $renderer
     */
    public function __construct(SourceRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(Image $image = null)
    {
        if ($image) {
            $this->image = $image;
        }

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function icon($attributes = [])
    {
        return $this->renderThumb(ScaleEnum::VALUE_ICON, $attributes);
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function small($attributes = [])
    {
        return $this->renderThumb(ScaleEnum::VALUE_SMALL, $attributes);
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function medium($attributes = [])
    {
        return $this->renderThumb(ScaleEnum::VALUE_MEDIUM, $attributes);
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function large($attributes = [])
    {
        return $this->renderThumb(ScaleEnum::VALUE_LARGE, $attributes);
    }

    protected function renderThumb($scale, $attributes)
    {
        if (!$this->image) {
            throw new \RuntimeException('Image is not defined');
        }

        $thumb = $this->image->getThumbnail($scale);

        return $thumb ? $this->render($thumb, $attributes) : '';
    }

    public function render(Thumbnail $thumb, $attributes = [])
    {
        $attributes         = array_merge(
            $attributes, ['src' => $this->renderer->assembleWebPath($thumb->getSource())]
        );
        $renderedAttributes = [];
        foreach ($attributes as $attrib => $value) {
            $renderedAttributes[] = $attrib . '="' . $this->getView()->escapeHtmlAttr($value) . '"';
        }

        return '<img ' . implode(' ', $renderedAttributes) . '>';
    }

    public function __toString()
    {
        return $this->image ? $this->render($this->image->getThumbnails()->first()) : '';
    }

}
