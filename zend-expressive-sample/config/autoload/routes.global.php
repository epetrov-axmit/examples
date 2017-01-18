<?php

use App\Middleware\GravatarMiddleware;
use Zend\Expressive\Router\AuraRouter;
use Zend\Expressive\Router\RouterInterface;

return [
    'dependencies' => [
        'invokables' => [
            RouterInterface::class => AuraRouter::class,
        ],
    ],

    'routes' => [
        [
            'name' => 'gravatar.byEmail',
            'path' => '/gravatar/{email}',
            'middleware' => GravatarMiddleware::class,
            'allowed_methods' => [
                'GET'
            ],
            'options' => [
                'tokens' => [
                    'email' => '[\w._%+-]+@[\w.-]+\.[a-z]{2,}'
                ]
            ]
        ]
    ],
];
