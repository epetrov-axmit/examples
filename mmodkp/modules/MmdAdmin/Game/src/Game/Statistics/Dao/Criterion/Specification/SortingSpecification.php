<?php

namespace MmdAdmin\Game\Statistics\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;
use Epos\Dao\Criterion\Specification\BuildEvent;
use Epos\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Epos\Dao\Criterion\Specification\SpecificationInterface;
use MmdAdmin\Game\Statistics\Dao\Criterion\GameStatisticsSpecification;

/**
 * Class SortingSpecification
 *
 * @package MmdAdmin\Game\Statistics\Dao\Criterion\Specification
 */
class SortingSpecification implements SpecificationInterface
{

    /**
     * @param Criteria                      $criteria
     * @param BuildEvent|DoctrineBuildEvent $event
     *
     * @return bool
     */
    public function apply(Criteria $criteria, BuildEvent $event)
    {
        $qb       = $event->getQueryBuilder();
        $ordering = $criteria->getOrderings();

        if (isset($ordering[GameStatisticsSpecification::SPEC_GUILDS_COUNT])) {
            $qb->addOrderBy(
                GuildsCountSpecification::FIELD_ALIAS,
                $ordering[GameStatisticsSpecification::SPEC_GUILDS_COUNT]
            );
            unset($ordering[GameStatisticsSpecification::SPEC_GUILDS_COUNT]);
        }

        if (isset($ordering[GameStatisticsSpecification::SPEC_MEMBERS_COUNT])) {
            $qb->addOrderBy(
                MembersCountSpecification::FIELD_ALIAS,
                $ordering[GameStatisticsSpecification::SPEC_MEMBERS_COUNT]
            );
            unset($ordering[GameStatisticsSpecification::SPEC_MEMBERS_COUNT]);
        }

        if (!empty($ordering)) {
            $criteria->orderBy($ordering);
            $qb->addCriteria($criteria);
        }

        return true;
    }
}
