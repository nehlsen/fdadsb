<?php

namespace Fda\TournamentBundle\Engine\LeaderBoard;

use Fda\PlayerBundle\Entity\Player;

interface LeaderBoardEntryInterface
{
    /**
     * @return Player
     */
    public function getPlayer();

    /**
     * some value which should be used when displaying the leader board
     *
     * @return int|string
     */
    public function getPoints();

    /**
     * whether the position of this entry is final, all games done in this round
     *
     * @return bool
     */
    public function isFinal();
}
