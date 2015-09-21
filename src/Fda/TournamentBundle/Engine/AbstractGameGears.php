<?php

namespace Fda\TournamentBundle\Engine;

abstract class AbstractGameGears extends EngineAware implements GameGearsInterface
{
    /**
     * {@InheritDoc}
     */
    public function getLegGears()
    {
        $legGears = $this->engine->getLegGears();
        $legGears->setGameGears($this);
        $legGears->setLeg($this->getCurrentLeg());
        return $legGears;
    }
}
