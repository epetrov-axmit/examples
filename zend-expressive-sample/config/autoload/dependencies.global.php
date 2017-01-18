<?php

use App\Middleware\Factory\GravatarMiddlewareFactory;
use App\Middleware\GravatarMiddleware;
use App\Service\Factory\Gravatar\GravatarServiceFactory;
use App\Service\Factory\Gravatar\IsReachableValidatorFactory;
use App\Service\Gravatar\GravatarService;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;

return [
    'dependencies' => [
        'factories' => [
            // Application
            Application::class                                => ApplicationFactory::class,

            // Gravatar
            GravatarMiddleware::class                         => GravatarMiddlewareFactory::class,
            GravatarService::class                            => GravatarServiceFactory::class,
            \App\Service\Gravatar\IsReachableValidator::class => IsReachableValidatorFactory::class,
        ]
    ]
];
