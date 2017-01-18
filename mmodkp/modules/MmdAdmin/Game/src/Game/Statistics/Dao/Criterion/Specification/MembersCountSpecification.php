<?php

namespace MmdAdmin\Game\Statistics\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;
use Epos\Dao\Criterion\Specification\BuildEvent;
use Epos\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Epos\Dao\Criterion\Specification\SpecificationInterface;
use Mmd\Guild\Entity\Member;

/**
 * Class MembersCountSpecification
 *
 * @package MmdAdmin\Game\Statistics\Dao\Criterion\Specification
 */
class MembersCountSpecification implements SpecificationInterface
{

    const FIELD_ALIAS = 'members_count';
    const TABLE_ALIAS = 'mem';

    /**
     * @param Criteria                      $criteria
     * @param BuildEvent|DoctrineBuildEvent $event
     *
     * @return bool
     */
    public function apply(Criteria $criteria, BuildEvent $event)
    {
        $qb      = $event->getQueryBuilder();
        $aliases = $event->getAliases();

        if (!in_array(GuildsCountSpecification::TABLE_ALIAS, $aliases)) {
            return false;
        }

        $qb->addSelect(sprintf('COUNT(DISTINCT %s.id) AS %s', self::TABLE_ALIAS, self::FIELD_ALIAS))
           ->leftJoin(
               Member::class,
               self::TABLE_ALIAS,
               'WITH',
               sprintf('%s.guild = %s.id', self::TABLE_ALIAS, GuildsCountSpecification::TABLE_ALIAS)
           );

        $event->addAlias(self::FIELD_ALIAS);

        return true;
    }
}
