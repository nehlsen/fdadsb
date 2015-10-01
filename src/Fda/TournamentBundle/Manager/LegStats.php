<?php

namespace Fda\TournamentBundle\Manager;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Entity\Leg;

class LegStats
{
    /** @var Leg */
    protected $leg;

    /**
     * LegStats constructor.
     * @param Leg $leg
     */
    public function __construct(Leg $leg)
    {
        $this->leg = $leg;
    }

    public function getCountTurns()
    {
        return count($this->leg->getTurns());
    }

    public function getCountTurnsOf(Player $player)
    {
        return count($this->leg->getTurnsOf($player));
    }

    public function getCountShots()
    {
        return $this->getCountShotsOf($this->leg->getGame()->getPlayer1()) +
               $this->getCountShotsOf($this->leg->getGame()->getPlayer2());
    }

    public function getCountShotsOf(Player $player)
    {
        if ($player == $this->leg->getGame()->getPlayer1()) {
            return $this->leg->getPlayer1shots();
        } elseif ($player == $this->leg->getGame()->getPlayer2()) {
            return $this->leg->getPlayer2shots();
        } else {
            return 0;
        }
    }
}
