<?php

namespace Fda\TournamentBundle\Engine;

use Fda\PlayerBundle\Entity\Player;
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
     * @return Leg
     */
    public function getLeg();

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

    /**
     * the score required to win the leg
     *
     * @return int
     */
    public function getRequiredScore();

    /**
     * the score for specified player
     *
     * @param Player $player
     *
     * @return int
     */
    public function getScoreOf(Player $player);

    /**
     * the required remaining score for specified player
     *
     * @param Player $player
     *
     * @return int
     */
    public function getRemainingScoreOf(Player $player);

    /**
     * try to find a list of shots to finish this game
     *
     * an empty array is returned if it is not possible or at least not possible to determine
     *
     * @param Player $player
     *
     * @return Arrow[]
     */
    public function getFinishingMovesOf(Player $player);
}
