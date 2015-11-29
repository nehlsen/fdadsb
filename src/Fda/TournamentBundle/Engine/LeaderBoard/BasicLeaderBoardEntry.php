<?php

namespace Fda\TournamentBundle\Engine\LeaderBoard;

use Fda\PlayerBundle\Entity\Player;

class BasicLeaderBoardEntry implements LeaderBoardEntryInterface
{
    /** @var Player */
    protected $player;

    /** @var int */
    protected $points;

    /** @var bool */
    protected $isFinal;

    /**
     * create a new entry
     *
     * @param Player    $player
     * @param int       $points
     * @param bool|true $isFinal
     *
     * @return BasicLeaderBoardEntry
     */
    public static function create(Player $player, $points, $isFinal = true)
    {
        $entry = new self();
        $entry->player = $player;
        $entry->points = (int)$points;
        $entry->isFinal = (bool)$isFinal;

        return $entry;
    }

    /**
     * @inheritDoc
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @inheritDoc
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @inheritDoc
     */
    public function isFinal()
    {
        return $this->isFinal;
    }

    /**
     * @param bool $isFinal
     */
    public function setFinal($isFinal = true)
    {
        $this->isFinal = $isFinal;
    }
}
