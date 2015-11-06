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
     * @return LegGearsInterface
     */
//    public function getLegGears();
}
