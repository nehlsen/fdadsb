<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Entity\Tournament;

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
}
