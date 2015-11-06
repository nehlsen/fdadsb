<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Entity\Tournament;

interface TournamentEngineInterface
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
}
