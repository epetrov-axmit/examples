<?php

namespace MmdAdmin\Game\Statistics\Service\Factory;

use Interop\Container\ContainerInterface;
use Mmd\Game\Dao\GameDaoInterface;
use MmdAdmin\Game\Statistics\Dao\GameStatisticsDao;
use MmdAdmin\Game\Statistics\Service\GameStatisticsService;

/**
 * Class GameStatisticsServiceFactory
 *
 * @package MmdAdmin\Game\Statistics\Service
 */
class GameStatisticsServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return GameStatisticsService
     */
    function __invoke(ContainerInterface $container)
    {
        $gameStatisticsDao = $container->get(GameStatisticsDao::class);
        $gameDoctrineDao   = $container->get(GameDaoInterface::class);

        return new GameStatisticsService($gameStatisticsDao, $gameDoctrineDao);
    }

}
