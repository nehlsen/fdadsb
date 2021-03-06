<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Entity\Tournament;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface TournamentEngineInterface extends EventSubscriberInterface
{
    /**
     * make sure engine is ready
     */
    public function ensureReady();

    /**
     * whether we have a tournament
     *
     * @return bool
     */
    public function hasTournament();

    /**
     * get the tournament
     *
     * @return Tournament|null
     */
    public function getTournament();

    /**
     * set the used tournament
     *
     * by default the engine will automatically use the first found open tournament
     * to analyze an old tournament, it can be overridden
     *
     * @param Tournament $tournament
     */
    public function setTournament(Tournament $tournament);

    /**
     * @return RoundGearsInterface
     */
    public function getCurrentRoundGears();

    /**
     * get the current round number
     *
     * starting from 0 for the seed round
     *
     * @return int
     */
    public function getCurrentRoundNumber();

    /**
     * whether the associated tournament is completed
     * @return bool
     */
    public function isTournamentCompleted();

    /**
     * get the gears for a game by ID
     *
     * @param int $gameId
     *
     * @return GameGearsInterface
     */
    public function getGameGearsForGameId($gameId);

    /**
     * get game gears for currently running game on specified board
     * returns null if currently no game running on board
     *
     * @param int $boardId
     * @return GameGearsInterface
     */
    public function getGameGearsForBoardId($boardId);
}
