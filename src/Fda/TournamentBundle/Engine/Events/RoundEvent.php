<?php

namespace Fda\TournamentBundle\Engine\Events;

use Fda\TournamentBundle\Entity\Round;
use Symfony\Component\EventDispatcher\GenericEvent;

class RoundEvent extends GenericEvent
{
    /**
     * @return Round
     */
    public function getRound()
    {
        $round = $this->getArgument('round');
        return $round;
    }

    /**
     * @param Round $round
     */
    public function setRound(Round $round)
    {
        $this->setArgument('round', $round);
    }
}
