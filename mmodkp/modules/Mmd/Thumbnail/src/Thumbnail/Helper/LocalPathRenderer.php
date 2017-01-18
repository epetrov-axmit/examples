<?php

namespace Mmd\Thumbnail\Helper;

/**
 * Class LocalPathRenderer
 *
 * @package Mmd\Thumbnail\Helper
 */
class LocalPathRenderer implements SourceRendererInterface
{

    public function assembleWebPath($source)
    {
        if (!isset($_SERVER['DOCUMENT_ROOT'])) {
            return $source;
        }

        if (false !== strstr($source, $_SERVER['DOCUMENT_ROOT'])) {
            $normalized = str_replace($_SERVER['DOCUMENT_ROOT'], '', $source);
            $source     = '/' . ltrim($normalized, '/');
        }

        return $source;
    }
}
