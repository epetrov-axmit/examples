<?php

namespace MmdAdmin\Game\Statistics\Dao\Criterion;

use Epos\Dao\Criterion\Filter;
use Epos\Dao\Criterion\Hydrator\DefaultFilterHydrator;

class GameStatisticsHydrator extends DefaultFilterHydrator
{
    protected function hydrateDefault($name, $value, Filter $filter)
    {
        switch ($name) {
            case 'name':
                $filter->andConstraint($name)->contains($value);
                break;
            default:
                parent::hydrateDefault($name, $value, $filter);
                break;
        }
    }
}
