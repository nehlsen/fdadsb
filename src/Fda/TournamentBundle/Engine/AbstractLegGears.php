<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Leg;
use Fda\TournamentBundle\Entity\Tournament;

abstract class AbstractLegGears extends EngineAware implements LegGearsInterface
{
    /** @var GameGearsInterface */
    protected $gameGears;

    /** @var Leg */
    protected $leg;

    /**
     * {@InheritDoc}
     */
    public function setGameGears(GameGearsInterface $gameGears)
    {
        $this->gameGears = $gameGears;
    }

    /**
     * {@InheritDoc}
     */
    public function setLeg(Leg $leg)
    {
        $this->leg = $leg;
    }

    /**
     * {@InheritDoc}
     */
    public function getLeg()
    {
        return $this->leg;
    }

    /**
     * abbreviation for this:leg:getGame()
     *
     * @return Game
     */
    protected function getGame()
    {
        return $this->leg->getGame();
    }

    /**
     * abbreviation for this:leg:getGame():getTournament()
     *
     * @return Tournament
     */
    protected function getTournament()
    {
        return $this->leg->getGame()->getTournament();
    }
}
