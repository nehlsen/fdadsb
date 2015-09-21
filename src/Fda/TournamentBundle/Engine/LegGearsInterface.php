<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Entity\Leg;
use Fda\TournamentBundle\Entity\Turn;

interface LegGearsInterface
{
    /**
     * set 'parent' gears
     *
     * @param GameGearsInterface $gameGears
     */
    public function setGameGears(GameGearsInterface $gameGears);

    /**
     * @param Leg $leg
     */
    public function setLeg(Leg $leg);

    /**
     * @return Turn
     */
    public function currentTurn();

    /**
     * @param int    $score
     * @param string $multiplier
     *
     * @return int remaining shots in this turn
     */
    public function registerShot($score, $multiplier = Turn::MULTIPLIER_SINGLE);

    /**
     * @return int
     */
    public function remainingShots();
}
