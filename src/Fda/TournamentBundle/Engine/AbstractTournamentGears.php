<?php

namespace Fda\TournamentBundle\Engine;

abstract class AbstractTournamentGears extends EngineAware implements TournamentGearsInterface
{
    /**
     * {@InheritDoc}
     */
    public function getGameGears($gameId)
    {
        $gameGears = $this->engine->getGameGears();
        $gameGears->setTournamentGears($this);
        $gameGears->setGame($gameId);
        return $gameGears;
    }
}
