<?php

namespace Mmd\Thumbnail\Helper\Factory;

use Interop\Container\ContainerInterface;
use Mmd\Thumbnail\Helper\AwsS3SourceRenderer;

/**
 * Class AwsS3SourceRendererFactory
 *
 * @package Mmd\Thumbnail\Helper\Factory
 */
class AwsS3SourceRendererFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $s3Helper = $container->get('ViewHelperManager')->get('s3Link');

        return new AwsS3SourceRenderer($s3Helper);
    }
}
