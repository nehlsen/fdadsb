<?php

namespace Fda\TournamentBundle\Engine;

abstract class AbstractGameGears extends EngineAware implements GameGearsInterface
{
    /** @var TournamentGearsInterface */
    protected $tournamentGears;

    /**
     * {@InheritDoc}
     */
    public function setTournamentGears(TournamentGearsInterface $tournamentGears)
    {
        $this->tournamentGears = $tournamentGears;
    }
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

    protected function getTournament()
    {
        return $this->engine->getTournament();
    }
}
