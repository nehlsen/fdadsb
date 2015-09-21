<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Entity\Tournament;
use Fda\TournamentBundle\Entity\Turn;

interface EngineInterface
{
    /**
     * whether we have an active tournament
     *
     * @return bool
     */
    public function hasTournament();

    /**
     * get the active tournament or null if we do not have an active tournament
     *
     * @return Tournament|null
     */
    public function getTournament();

    /**
     * persist the provided new tournament
     *
     * @param Tournament $tournament
     *
     * @throws \Exception if we already have an active tournament
     */
    public function createTournament(Tournament $tournament);

    /**
     * @param int    $gameId
     * @param int    $score
     * @param string $multiplier
     *
     * @return bool true if game continues, false else
     */
    public function registerShot($gameId, $score, $multiplier = Turn::MULTIPLIER_SINGLE);

    /**
     * @return TournamentGearsInterface
     */
    public function getGears();

    /**
     * @return GameGearsInterface
     */
    public function getGameGears();

    /**
     * @return LegGearsInterface
     */
    public function getLegGears();
}
