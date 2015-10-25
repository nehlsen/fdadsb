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
}
