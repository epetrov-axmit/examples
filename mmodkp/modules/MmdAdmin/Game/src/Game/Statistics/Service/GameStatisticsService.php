<?php

namespace MmdAdmin\Game\Statistics\Service;

use Epos\Dao\Criterion\Filter;
use Mmd\Game\Dao\GameDaoInterface;
use Mmd\Game\Entity\Game;
use MmdAdmin\Game\Statistics\Dao\Criterion\GameStatisticsHydrator;
use MmdAdmin\Game\Statistics\Dao\GameStatisticsDao;
use MmdAdmin\Game\Statistics\Dto\StatisticsCountCharactersInGameDto;
use MmdAdmin\Game\Statistics\Dto\StatisticsCountGuildsInGameDto;
use Zend\Paginator\Paginator;

/**
 * Class GameStatisticsService
 *
 * @package MmdAdmin\Game\Statistics\Service
 */
class GameStatisticsService
{
    /**
     * @var GameStatisticsDao
     */
    protected $gameStatisticsDao;

    /**
     * @var GameDaoInterface
     */
    protected $gameDao;

    /**
     * GameStatisticsService constructor.
     *
     * @param GameStatisticsDao $gameStatisticsDao
     * @param GameDaoInterface  $gameDao
     *
     * @internal param GameStatisticsDao $gameStatisticDao
     */
    public function __construct(GameStatisticsDao $gameStatisticsDao, GameDaoInterface $gameDao)
    {
        $this->gameStatisticsDao = $gameStatisticsDao;
        $this->gameDao           = $gameDao;
    }

    /**
     * @param array $params
     *
     * @return Paginator
     */
    public function findGamesPopulation(array $params = [])
    {
        $filter = new Filter();
        $filter->fromArray($params, new GameStatisticsHydrator());

        return $this->gameStatisticsDao->gamePopulationByFilter($filter);
    }
}
