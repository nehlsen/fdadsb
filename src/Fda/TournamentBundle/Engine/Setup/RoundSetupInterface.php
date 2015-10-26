<?php

namespace Fda\TournamentBundle\Engine\Setup;

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
     * get a human readable label for the implementing mode
     *
     * @return string
     */
    public function getModeLabel();
}
