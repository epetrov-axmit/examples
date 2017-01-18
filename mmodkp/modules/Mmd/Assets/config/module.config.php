<?php

use Mmd\Assets\Console\EnsureAssetsCommand;
use Mmd\Assets\Console\Factory\EnsureAssetsCommandFactory;
use Mmd\Assets\Map\Factory\MapFactory;
use Mmd\Assets\Map\Filter\FilterManager;
use Mmd\Assets\Map\Map;
use Mmd\Assets\View\Factory\Helper\AssetResolverFactory;
use Mmd\Assets\View\Helper\AssetResolver;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            Map::class                 => MapFactory::class,
            FilterManager::class       => InvokableFactory::class,
            EnsureAssetsCommand::class => EnsureAssetsCommandFactory::class,
        ],
    ],
    'view_helpers'    => [
        'aliases'   => [
            'assetResolver' => AssetResolver::class,
        ],
        'factories' => [
            AssetResolver::class => AssetResolverFactory::class,
        ],
    ],
    'mmd.console'     => [
        'commands' => [
            EnsureAssetsCommand::class,
        ],
    ],
];
