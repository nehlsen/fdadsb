<?php

namespace Fda\TournamentBundle\Engine\Events;

use Fda\TournamentBundle\Engine\Bolts\Arrow;
use Symfony\Component\EventDispatcher\GenericEvent;

class ArrowEvent extends GenericEvent
{
    /**
     * @return Arrow
     */
    public function getArrow()
    {
        $arrow = $this->getArgument('arrow');
        return $arrow;
    }

    /**
     * @param Arrow $arrow
     */
    public function setArrow(Arrow $arrow)
    {
        $this->setArgument('arrow', $arrow);
    }
}
