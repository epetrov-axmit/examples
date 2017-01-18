<?php

namespace MmdAdmin\Game\Statistics\Dao\Criterion;

use Epos\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;

/**
 * Class GameStatisticsSpecification
 *
 * @package MmdAdmin\Game\Statistics\Dao\Criterion
 */
class GameStatisticsSpecification extends DoctrineSpecificationManager
{
    const SPEC_GUILDS_COUNT  = 'guilds-count';
    const SPEC_MEMBERS_COUNT = 'members-count';
}
