<?php

namespace MmdAdmin\Game\Statistics\Dao\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Mmd\Game\Entity\Game;
use MmdAdmin\Game\Statistics\Dao\Criterion\GameStatisticsSpecification;
use MmdAdmin\Game\Statistics\Dao\GameStatisticsDao;

class GameStatisticsDaoFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $objectManager  = $container->get(EntityManager::class);
        $specifications = $container->get(GameStatisticsSpecification::class);

        return new GameStatisticsDao($objectManager, Game::class, $specifications);
    }
}
