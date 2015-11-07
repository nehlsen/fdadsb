<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Bolts\Arrow;
use Fda\TournamentBundle\Entity\Turn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface LegGearsInterface extends EventSubscriberInterface
{
    /**
     * @return Turn
     */
    public function getCurrentTurn();

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
