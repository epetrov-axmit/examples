<?php

namespace MmdAdmin\Game\Statistics\Dto;

use Mmd\Game\Entity\Game;

/**
 * Class GamePopulationTo
 *
 * @package MmdAdmin\Game\Statistics\Dto
 */
class GamePopulationTo
{
    /**
     * @var Game
     */
    protected $game;

    /**
     * @var int
     */
    protected $guildsCount = 0;

    /**
     * @var int
     */
    protected $membersCount = 0;

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     *
     * @return GamePopulationTo
     */
    public function setGame(Game $game): GamePopulationTo
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return int
     */
    public function getGuildsCount(): int
    {
        return $this->guildsCount;
    }

    /**
     * @param int $guildsCount
     *
     * @return GamePopulationTo
     */
    public function setGuildsCount($guildsCount): GamePopulationTo
    {
        $this->guildsCount = (int)$guildsCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getMembersCount(): int
    {
        return $this->membersCount;
    }

    /**
     * @param int $membersCount
     *
     * @return GamePopulationTo
     */
    public function setMembersCount($membersCount): GamePopulationTo
    {
        $this->membersCount = (int)$membersCount;

        return $this;
    }
}
