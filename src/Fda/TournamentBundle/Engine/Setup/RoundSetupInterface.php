<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Bolts\GameMode;
use Fda\TournamentBundle\Engine\Bolts\LegMode;

interface RoundSetupInterface
{
    /**
     * how many players will advance from this round
     *
     * @param int $number
     * @return RoundSetupInterface this
     */
    public function setAdvance($number);

    /**
     * number of players per group who will advance to the next round
     *
     * -1 means all
     *
     * @return int
     */
    public function getAdvance();

    /**
     * get the input mode for this round
     *
     * @return InputInterface
     */
    public function getInput();

    /**
     * @param GameMode $mode
     * @return RoundSetupInterface this
     */
    public function setGameMode(GameMode $mode);

    /**
     * @return GameMode
     */
    public function getGameMode();

    /**
     * @param LegMode $mode
     * @return RoundSetupInterface this
     */
    public function setLegMode(LegMode $mode);

    /**
     * @return LegMode
     */
    public function getLegMode();

    /**
     * get a human readable label for the implementing mode
     *
     * @return string
     */
    public function getModeLabel();
}
