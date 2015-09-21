<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Leg;

interface GameGearsInterface
{
    /**
     * @param int|Game $game_or_id
     */
    public function setGame($game_or_id);

    /**
     * @return Game
     */
    public function getGame();

    /**
     * @return LegGearsInterface
     */
    public function getLegGears();

    /**
     * @return Leg
     */
    public function getCurrentLeg();
}
