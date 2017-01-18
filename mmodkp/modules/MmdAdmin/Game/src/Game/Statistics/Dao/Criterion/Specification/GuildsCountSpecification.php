<?php

namespace MmdAdmin\Game\Statistics\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;
use Epos\Dao\Criterion\Specification\BuildEvent;
use Epos\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Epos\Dao\Criterion\Specification\SpecificationInterface;
use Mmd\Guild\Entity\Guild;

/**
 * Class GuildsCountSpecification
 *
 * @package MmdAdmin\Game\Statistics\Dao\Criterion\Specification
 */
class GuildsCountSpecification implements SpecificationInterface
{

    const FIELD_ALIAS = 'guilds_count';
    const TABLE_ALIAS = 'guild';

    /**
     * @param Criteria                      $criteria
     * @param BuildEvent|DoctrineBuildEvent $event
     *
     * @return bool
     */
    public function apply(Criteria $criteria, BuildEvent $event)
    {
        $qb        = $event->getQueryBuilder();
        $rootAlias = $event->getRootAlias();

        $qb->addSelect(sprintf('COUNT(DISTINCT %s.id) AS %s', self::TABLE_ALIAS, self::FIELD_ALIAS))
           ->leftJoin(
               Guild::class,
               self::TABLE_ALIAS,
               'WITH',
               sprintf('%s.game = %s.id', self::TABLE_ALIAS, $rootAlias)
           );

        $event->addAlias(self::TABLE_ALIAS);

        return true;
    }
}
