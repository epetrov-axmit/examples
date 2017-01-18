<?php

namespace MmdAdmin\Game\Controller\Factory;

use Epos\UserCore\Service\UserService;
use Interop\Container\ContainerInterface;
use Mmd\Game\Service\GameService;
use Mmd\Util\ServiceLocator\ExtractServiceLocatorTrait;
use MmdAdmin\Game\Controller\GameController;
use MmdAdmin\Game\Statistics\Service\GameStatisticsService;

/**
 * Class GameControllerFactory
 *
 * @package MmdAdmin\Game\Controller\Factory
 */
class GameControllerFactory
{
    use ExtractServiceLocatorTrait;

    /**
     * @param ContainerInterface $container
     *
     * @return GameController
     */
    public function __invoke(ContainerInterface $container)
    {
        $sm = $this->extractServiceLocator($container);

        $userService           = $sm->get(UserService::class);
        $gameService           = $sm->get(GameService::class);
        $gameStatisticsService = $sm->get(GameStatisticsService::class);
        $formContainer         = $sm->get('FormElementManager');

        return new GameController($gameService, $userService, $gameStatisticsService, $formContainer);
    }
}