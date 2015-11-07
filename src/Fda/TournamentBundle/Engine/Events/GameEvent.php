<?php

namespace Fda\TournamentBundle\Engine\Events;

use Fda\TournamentBundle\Entity\Game;
use Symfony\Component\EventDispatcher\GenericEvent;

class GameEvent extends GenericEvent
{
    /**
     * @return Game
     */
    public function getGame()
    {
        $game = $this->getArgument('game');
        return $game;
    }

    /**
     * @param Game $game
     */
    public function setGame(Game $game)
    {
        $this->setArgument('game', $game);
    }
}
