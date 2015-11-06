<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\Factory\LegGearsFactory;
use Fda\TournamentBundle\Entity\Game;

abstract class AbstractGameGears implements GameGearsInterface
{
    /** @var Game */
    private $game;

    /** @var LegGearsFactory */
    private $legGearsFactory;

    /** @var LegGearsInterface[] */
    private $legGears;

    public function __construct(Game $game)
    {
        // game provides access to group, round, tournament, setup, players, etc...

        $this->game = $game;
    }

    /**
     * @inheritDoc
     */
    public function setLegGearsFactory(LegGearsFactory $legGearsFactory)
    {
        $this->legGearsFactory = $legGearsFactory;
    }

    /**
     * @inheritDoc
     */
    public function getGame()
    {
        return $this->game;
    }
}
