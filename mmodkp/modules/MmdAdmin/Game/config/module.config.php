<?php

use MmdAdmin\Game\Controller\Factory\GameControllerFactory;
use MmdAdmin\Game\Controller\Factory\ServerControllerFactory;
use MmdAdmin\Game\Controller\GameController;
use MmdAdmin\Game\Controller\ServerController;
use MmdAdmin\Game\Statistics\Dao\Criterion\Factory\GameStatisticsSpecificationFactory;
use MmdAdmin\Game\Statistics\Dao\Criterion\GameStatisticsSpecification;
use MmdAdmin\Game\Statistics\Dao\Factory\GameStatisticsDaoFactory;
use MmdAdmin\Game\Statistics\Dao\GameStatisticsDao;
use MmdAdmin\Game\Statistics\Service\Factory\GameStatisticsServiceFactory;
use MmdAdmin\Game\Statistics\Service\GameStatisticsService;
use MmdAdmin\Game\Statistics\View\Form\Helper\GameStatisticsFilterForm;

return [
    'router'          => [
        'routes' => [
            'admin' => [
                'type'          => 'Literal',
                'options'       => [
                    'route' => '/admin',
                ],
                'may_terminate' => false,
                'child_routes'  => [
                    'game'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/game[/:action][/:id]',
                            'defaults'    => [
                                'controller' => GameController::class,
                                'action'     => 'index',
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                        ],
                    ],
                    'server' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/server[/:action][/:id]',
                            'defaults'    => [
                                'controller' => ServerController::class,
                                'action'     => 'index',
                            ],
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'MmdAdmin\Game\Controller\Game'   => 'MmdAdmin\Game\Controller\GameController',
            'MmdAdmin\Game\Controller\Server' => 'MmdAdmin\Game\Controller\ServerController',
        ],
        'factories'  => [
            GameController::class   => GameControllerFactory::class,
            ServerController::class => ServerControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'controller_map'      => [
            GameController::class   => 'mmd-admin-game/game',
            ServerController::class => 'mmd-admin-game/server',
        ],
    ],
    'service_manager' => [
        'factories' => [
            GameStatisticsService::class       => GameStatisticsServiceFactory::class,
            GameStatisticsDao::class           => GameStatisticsDaoFactory::class,
            GameStatisticsSpecification::class => GameStatisticsSpecificationFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'mmdGameStatisticsFilterForm' => GameStatisticsFilterForm::class,
        ],
    ],
    'navigation'      => [
        'default' => [
            'admin' => [
                'label' => 'Админка',
                'route' => 'admin/game',
                'class' => 'sa-side-chart',
                'pages' => [
                    [
                        'label' => 'Игры',
                        'route' => 'admin/game',
                    ],
                    [
                        'label' => 'Сервера',
                        'route' => 'admin/server',
                    ],
                ],
            ],
        ],
    ],
];
