<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Entity\Game;

class TournamentGearsAllVsAll extends AbstractTournamentGears
{
    /** @var Game[] */
    protected $allGames;

    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function getAllGames()
    {
        if (null === $this->allGames) {
            // all vs all knows all games in advance,
            //  load all games from DB, if empty we have not initialized the games, create them
            $games = $this->engine->getTournament()->getGames();
            if (count($games) < 1) {
                $games = $this->determineAllGames();

                foreach ($games as $game) {
                    $this->entityManager->persist($game);
                }
                $this->entityManager->flush();
            }

            $this->allGames = $games;
        }

        return $this->allGames;
    }

    /**
     * @inheritDoc
     */
    public function getUpcomingGames(Player $referee = null)
    {
        // filter out all games which are already done or at least running
        // filter out all games where referee takes part (referee can not evaluate himself)

        $upcomingGames = array();
        foreach ($this->getAllGames() as $game) {
//            if (!$game->isRunning()) {
//                continue;
//            }
            if ($game->isClosed()) {
                continue;
            }

            $upcomingGames[] = $game;
        }

        return $upcomingGames;
    }

    /**
     * creates a list of all games for the associated tournament
     *
     * @return Game[]
     */
    protected function determineAllGames()
    {
        $players = $this->engine->getTournament()->getPlayers();
        $players = $players instanceof Collection ? $players->toArray() : $players;

        $games = array();
        while (count($players) > 1) {
            $player = array_shift($players);
            $games = array_merge(
                $games,
                $this->determineGames($player, $players)
            );
        }

        return $games;
    }

    /**
     * generates a list of games between player and all provided contestants
     *
     * @param Player   $player
     * @param Player[] $contestants
     *
     * @return Game[]
     */
    protected function determineGames($player, $contestants)
    {
        $games = array();
        foreach ($contestants as $contestant) {
            $game = Game::prepare(
                $player,
                $contestant,
                $this->engine->getTournament()
            );

            $games[] = $game;
        }

        return $games;
    }

    /**
     * @inheritDoc
     */
    public function onGameComplete(Game $game)
    {
        // check for tournament winner
        // TODO: Implement onGameComplete() method.
//        throw new \Exception('onGameComplete: check if we have a tournament winner');
    }
}
