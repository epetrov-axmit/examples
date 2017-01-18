<?php

namespace Mmd\Assets\View\Factory\Helper;

use Interop\Container\ContainerInterface;
use Mmd\Assets\Map\Map;
use Mmd\Assets\View\Helper\AssetResolver;
use Mmd\Util\ServiceLocator\ExtractServiceLocatorTrait;

/**
 * Class AssetResolverFactory
 *
 * @package Mmd\Assets\View\Factory\Helper
 */
class AssetResolverFactory
{
    use ExtractServiceLocatorTrait;

    public function __invoke(ContainerInterface $container)
    {
        $sm  = $this->extractServiceLocator($container);
        $map = $sm->get(Map::class);

        return new AssetResolver($map);
    }
}
