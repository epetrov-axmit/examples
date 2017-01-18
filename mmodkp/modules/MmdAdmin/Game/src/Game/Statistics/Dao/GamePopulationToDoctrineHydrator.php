<?php

namespace MmdAdmin\Game\Statistics\Dao;

use Mmd\Game\Entity\Game;
use MmdAdmin\Game\Statistics\Dao\Criterion\Specification\GuildsCountSpecification;
use MmdAdmin\Game\Statistics\Dao\Criterion\Specification\MembersCountSpecification;
use MmdAdmin\Game\Statistics\Dto\GamePopulationTo;
use Zend\Filter\Exception;
use Zend\Filter\FilterInterface;

/**
 * Class GamePopulationToDoctrineHydrator
 *
 * @package MmdAdmin\Game\Statistics\Dao
 */
class GamePopulationToDoctrineHydrator implements FilterInterface
{

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     *
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if (!isset($value[0]) || !$value[0] instanceof Game) {
            throw new \InvalidArgumentException(
                sprintf('First value in the array has to be instance of %s', Game::class)
            );
        }

        $dto = new GamePopulationTo();
        $dto->setGame($value[0]);
        $dto->setGuildsCount(
            isset($value[GuildsCountSpecification::FIELD_ALIAS]) ? $value[GuildsCountSpecification::FIELD_ALIAS] : 0
        );
        $dto->setMembersCount(
            isset($value[MembersCountSpecification::FIELD_ALIAS]) ? $value[MembersCountSpecification::FIELD_ALIAS] : 0
        );

        return $dto;
    }
}
