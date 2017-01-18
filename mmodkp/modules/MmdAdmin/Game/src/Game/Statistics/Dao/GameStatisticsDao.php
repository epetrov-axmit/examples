<?php

namespace MmdAdmin\Game\Statistics\Dao;

use Application\Filter\CollectionFilter;
use Epos\Dao\Criterion\Filter;
use Epos\Dao\Dao\AbstractDoctrineDao;
use Mmd\Game\Entity\Game;
use Mmd\Guild\Entity\Guild;
use Mmd\Guild\Entity\Member;
use MmdAdmin\Game\Statistics\Dao\Criterion\GameStatisticsSpecification;
use Zend\Paginator\Paginator;

/**
 * Class GameStatisticsDao
 *
 * @package MmdAdmin\Game\Statistics\Dao
 */
class GameStatisticsDao extends AbstractDoctrineDao
{
    /**
     * @param Filter $filter
     *
     * @return array
     */
    public function guildsCountByFilter(Filter $filter)
    {
        $qb = $this->objectManager->createQueryBuilder();
        $qb->select($qb->expr()->count('guild.id'))
           ->from(Guild::class, 'guild')
           ->groupBy('guild.id');
        $query = $this->getFilteredQuery($qb, $filter);

        return $query->getScalarResult();
    }

    /**
     * @param Filter $filter
     *
     * @return mixed
     */
    public function membersCountByFilter(Filter $filter)
    {
        $qb = $this->objectManager->createQueryBuilder();
        $qb->select($qb->expr()->count('mem.id'))
           ->from(Guild::class, 'guild')
           ->leftJoin(Member::class, 'mem', 'WITH', 'mem.guild = guild.id')
           ->groupBy('guild.id');
        $query = $this->getFilteredQuery($qb, $filter);

        return $query->getScalarResult();
    }

    /**
     * @param Filter $filter
     *
     * @return Paginator
     */
    public function gamePopulationByFilter(Filter $filter)
    {
        $qb = $this->objectManager->createQueryBuilder();
        $qb->select('game')
           ->from(Game::class, 'game')
           ->groupBy('game.id');

        $filter->andConstraint(GameStatisticsSpecification::SPEC_GUILDS_COUNT)->value(null);
        $filter->andConstraint(GameStatisticsSpecification::SPEC_MEMBERS_COUNT)->value(null);

        $paginator = $this->getPaginator(
            $this->getFilteredQuery($qb, $filter), $filter->getLimit(), $filter->getOffset()
        );
        $paginator->setFilter(new CollectionFilter(new GamePopulationToDoctrineHydrator()));

        return $paginator;
    }
}
