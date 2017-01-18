<?php

namespace MmdAdmin\Game\Statistics\Dao\Criterion\Factory;

use Epos\Dao\Criterion\Filter;
use Interop\Container\ContainerInterface;
use MmdAdmin\Game\Statistics\Dao\Criterion\GameStatisticsSpecification;
use MmdAdmin\Game\Statistics\Dao\Criterion\Specification\GuildsCountSpecification;
use MmdAdmin\Game\Statistics\Dao\Criterion\Specification\MembersCountSpecification;
use MmdAdmin\Game\Statistics\Dao\Criterion\Specification\SortingSpecification;

/**
 * Class GameStatisticsSpecificationFactory
 *
 * @package MmdAdmin\Game\Statistics\Dao\Criterion\Factory
 */
class GameStatisticsSpecificationFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $specification = new GameStatisticsSpecification();

        $specification->addSpecification(
            GameStatisticsSpecification::SPEC_GUILDS_COUNT,
            new GuildsCountSpecification()
        );

        $specification->addSpecification(
            GameStatisticsSpecification::SPEC_MEMBERS_COUNT,
            new MembersCountSpecification()
        );

        $specification->addSpecification(Filter::SORT_PARAMETER_NAME, new SortingSpecification());

        return $specification;
    }
}
