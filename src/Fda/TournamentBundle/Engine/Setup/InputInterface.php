<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;

interface InputInterface
{
    /**
     * get a human readable label for the implementing mode
     *
     * @return string
     */
    public function getModeLabel();

    /**
     * @param RoundGearsInterface $previousRoundGears
     *
     * @return Player[][]
     */
    public function filter(RoundGearsInterface $previousRoundGears);
}
