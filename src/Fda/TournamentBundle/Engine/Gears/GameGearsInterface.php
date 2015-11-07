<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\Bolts\GameMode;
use Fda\TournamentBundle\Engine\Factory\LegGearsFactory;
use Fda\TournamentBundle\Entity\Game;

interface GameGearsInterface
{
    /**
     * @param LegGearsFactory $legGearsFactory
     */
    public function setLegGearsFactory(LegGearsFactory $legGearsFactory);

    /**
     * @return Game
     */
    public function getGame();

    /**
     * @return GameMode
     */
//    public function getGameMode();

    /**
     * get leg gears for all completed and begun legs of this game
     *
     * @return LegGearsInterface[]
     */
//    public function getAllLegGears();

    /**
     * get leg-gears for current leg
     *
     * @return LegGearsInterface
     */
    public function getCurrentLegGears();
}
