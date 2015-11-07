<?php

namespace Fda\TournamentBundle\Engine\Events;

use Fda\TournamentBundle\Entity\Turn;
use Symfony\Component\EventDispatcher\GenericEvent;

class TurnEvent extends GenericEvent
{
    /**
     * @return Turn
     */
    public function getTurn()
    {
        $turn = $this->getArgument('turn');
        return $turn;
    }

    /**
     * @param Turn $turn
     */
    public function setTurn(Turn $turn)
    {
        $this->setArgument('turn', $turn);
    }
}
