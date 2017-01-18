<?php

use Mmd\Thumbnail\Helper\AwsS3SourceRenderer;
use Mmd\Thumbnail\Helper\Factory\AwsS3SourceRendererFactory;
use Mmd\Thumbnail\Helper\LocalPathRenderer;
use Mmd\Thumbnail\View\Factory\Helper\ThumbnailHelperFactory;
use Mmd\Thumbnail\View\Helper\ThumbnailHelper;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'mmd-assets'      => [
        'original_images' => [
            'width'   => 1280,
            'storage' => [
                'adapter' => 'AwsS3',
                'options' => [
                    'bucket' => 'img.mmodkp.com',
                    'prefix' => 'original',
                ],
            ],
        ],
        'thumbnails'      => [
            'storage' => [
                'adapter' => 'AwsS3',
                'options' => [
                    'bucket' => 'img.mmodkp.com',
                    'prefix' => 'thumbnails',
                ],
            ],
            'icon'    => [
                'width' => 32,
                'dir'   => 'icon',
            ],
            'small'   => [
                'width' => 120,
                'dir'   => 'small',
            ],
            'medium'  => [
                'width' => 480,
                'dir'   => 'medium',

            ],
            'large'   => [
                'width' => 960,
                'dir'   => 'large',
            ],
        ],
    ],
    'service_manager' => [
        'aliases'            => [
            'mmd.thumbnail.hydrator.imageStrategy'                  => 'Mmd\\Thumbnail\\Hydrator\\Strategy\\ImageStrategy',
            'mmd.thumbnail.defaultNamingStrategy'                   => 'Mmd\\Thumbnail\\Service\\DefaultNamingStrategy',
            'mmd.thumbnail.options.originalImage'                   => 'Mmd\\Thumbnail\\Options\\OriginalImageOptions',
            'mmd.thumbnail.strategy.listener.image'                 => 'Mmd\\Thumbnail\\Hydrator\\Strategy\\ImageStrategy\\ImageListener',
            'mmd.thumbnail.strategy.listener.thumbnails'            => 'Mmd\\Thumbnail\\Hydrator\\Strategy\\ImageStrategy\\ThumbnailsListener',
            'mmd.thumbnail.doctrine.listener.removeImageSource'     => 'Mmd\\Thumbnail\\Doctrine\\RemoveImageSourceListener',
            'mmd.thumbnail.doctrine.listener.removeThumbnailSource' => 'Mmd\\Thumbnail\\Doctrine\\RemoveThumbnailSourceListener',
        ],
        'invokables'         => [
            'mmd.imagine.gd'                             => 'Imagine\\Gd\\Imagine',
            'mmd.thumbnail.strategy.listener.removeTemp' => 'Mmd\\Thumbnail\\Hydrator\\Strategy\\ImageStrategy\\RemoveTempSourceListener',
        ],
        'factories'          => [
            'Mmd\\Thumbnail\\Hydrator\\Strategy\\ImageStrategy'                     => 'Mmd\\Thumbnail\\Hydrator\\Strategy\\Factory\\ImageStrategyFactory',
            'Mmd\\Thumbnail\\Options\\OriginalImageOptions'                         => 'Mmd\\Thumbnail\\Options\\Factory\\OriginalImageOptionsFactory',
            'Mmd\\Thumbnail\\Service\\DefaultNamingStrategy'                        => 'Mmd\\Thumbnail\\Service\\Factory\\DefaultNamingStrategyFactory',
            'Mmd\\Thumbnail\\Hydrator\\Strategy\\ImageStrategy\\ImageListener'      => 'Mmd\\Thumbnail\\Hydrator\\Strategy\\Factory\\ImageListenerFactory',
            'Mmd\\Thumbnail\\Hydrator\\Strategy\\ImageStrategy\\ThumbnailsListener' => 'Mmd\\Thumbnail\\Hydrator\\Strategy\\Factory\\ThumbnailsListenerFactory',
            'Mmd\\Thumbnail\\Doctrine\\RemoveImageSourceListener'                   => 'Mmd\\Thumbnail\\Doctrine\\Factory\\RemoveImageSourceListenerFactory',
            'Mmd\\Thumbnail\\Doctrine\\RemoveThumbnailSourceListener'               => 'Mmd\\Thumbnail\\Doctrine\\Factory\\RemoveThumbnailSourceListenerFactory',
            AwsS3SourceRenderer::class                                              => AwsS3SourceRendererFactory::class,
            LocalPathRenderer::class                                                => InvokableFactory::class,
        ],
        'abstract_factories' => [
            'Mmd\\Thumbnail\\Options\\Factory\\ThumbnailOptionsAbstractFactory',
            'Mmd\\Thumbnail\\Service\\Factory\\ImageServiceAbstractFactory',
        ],
        'delegators'         => [
            'Doctrine\\ORM\\EntityManager' => [
                'Mmd\\Thumbnail\\Doctrine\\Factory\\InjectRemoveSourceListenersDelegator',
            ],
        ],
        'shared'             => [
            AwsS3SourceRenderer::class => false,
        ],
    ],
    'view_helpers'    => [
        'aliases'   => [
            'mmdImageThumbnail' => ThumbnailHelper::class,
        ],
        'factories' => [
            ThumbnailHelper::class => ThumbnailHelperFactory::class,
        ],
    ],
    'doctrine'        => [
        'driver' => [
            'thumbnail_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/Thumbnail/Entity',
                ],
            ],
            'orm_default'        => [
                'drivers' => [
                    'Mmd\Thumbnail\Entity' => 'thumbnail_entities',
                ],
            ],
        ],
    ],
    'enums'           => [
        'ThumbnailScaleEnum' => 'Mmd\Thumbnail\Entity\Enum\ScaleEnum',
    ],
];
