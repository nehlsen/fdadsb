<?php

namespace Fda\TournamentBundle\Engine\Events;

use Fda\TournamentBundle\Entity\Leg;
use Symfony\Component\EventDispatcher\GenericEvent;

class LegEvent extends GenericEvent
{
    /**
     * @return Leg
     */
    public function getLeg()
    {
        $leg = $this->getArgument('leg');
        return $leg;
    }

    /**
     * @param Leg $leg
     */
    public function setLeg(Leg $leg)
    {
        $this->setArgument('leg', $leg);
    }
}
