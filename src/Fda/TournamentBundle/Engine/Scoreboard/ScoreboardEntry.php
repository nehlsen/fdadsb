<?php

namespace Fda\TournamentBundle\Engine\Scoreboard;

use Fda\PlayerBundle\Entity\Player;

class ScoreboardEntry
{
    /** @var Player */
    protected $player;

    /** @var int */
    protected $score;

    /** @var int */
    protected $isFinal;

    public function __construct(Player $player, $score, $isFinal)
    {
        if ((int)$score < 0) {
            throw new \InvalidArgumentException('negative score makes no sense');
        }

        $this->player = $player;
        $this->score = (int)$score;
        $this->isFinal = (bool)$isFinal;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * get score of player in this group for this round
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * whether this player is properly placed or just in the list for completeness
     *
     * a score greater than 0 will assume the player is placed
     *
     * @return bool
     */
    public function isPlaced()
    {
        return $this->getScore() > 0;
    }

    /**
     * whether the score of the player is final, all games done in this round
     *
     * @return bool
     */
    public function isFinal()
    {
        return $this->isFinal;
    }
}
