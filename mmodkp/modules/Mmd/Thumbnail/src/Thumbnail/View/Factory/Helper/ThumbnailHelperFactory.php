<?php

namespace Mmd\Thumbnail\View\Factory\Helper;

use Interop\Container\ContainerInterface;
use Mmd\Thumbnail\Helper\AwsS3SourceRenderer;
use Mmd\Thumbnail\View\Helper\ThumbnailHelper;
use Mmd\Util\ServiceLocator\ExtractServiceLocatorTrait;

/**
 * Class ThumbnailHelperFactory
 *
 * @package Mmd\Thumbnail\View\Factory\Helper
 */
class ThumbnailHelperFactory
{
    use ExtractServiceLocatorTrait;

    public function __invoke(ContainerInterface $container)
    {
        $sm       = $this->extractServiceLocator($container);
        $renderer = $sm->get(AwsS3SourceRenderer::class);
        $renderer->setBucket($this->extractBucket($sm));

        return new ThumbnailHelper($renderer);
    }

    private function extractBucket(ContainerInterface $container)
    {
        $config = $container->get('mmd-assets-config');
        $config = isset($config['thumbnails']['storage']['options']) ? $config['thumbnails']['storage']['options'] : [];

        if (empty($config['bucket'])) {
            throw new \RuntimeException('Thumbnails bucket is not defined');
        }

        return $config['bucket'];
    }
}
