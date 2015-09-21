<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Entity\Leg;

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
     * @param Leg $leg
     */
    public function setLeg(Leg $leg)
    {
        $this->leg = $leg;
    }
}
