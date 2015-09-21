<?php

namespace Fda\TournamentBundle\Engine;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Entity\Game;

interface TournamentGearsInterface
{
    /**
     * @param EngineInterface $engine
     */
    public function setEngine(EngineInterface $engine);

    /**
     * get all games (including finished)
     *
     * this may change over time, since games could be added later depending on the tournament mode
     *
     * @return Game[]
     */
    public function getAllGames();

    /**
     * get all upcoming games
     *
     * if this method returns no more games, he tournament is over
     * if referee is provided, only games for this referee are returned (e.g. a referee can not evaluate himself)
     * if no referee is provided, ALL upcoming games are returned
     *
     * @param Player $referee
     *
     * @return Game[]
     */
    public function getUpcomingGames(Player $referee = null);

    /**
     * get game gears for specified game
     *
     * @param int $gameId game ID
     *
     * @return GameGearsInterface
     */
    public function getGameGears($gameId);
}
