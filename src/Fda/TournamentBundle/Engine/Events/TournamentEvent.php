<?php

namespace Fda\TournamentBundle\Engine\Events;

use Fda\TournamentBundle\Entity\Tournament;
use Symfony\Component\EventDispatcher\GenericEvent;

class TournamentEvent extends GenericEvent
{
    /**
     * @return Tournament
     */
    public function getTournament()
    {
        $tournament = $this->getArgument('tournament');
        return $tournament;
    }

    /**
     * @param Tournament $tournament
     */
    public function setTournament(Tournament $tournament)
    {
        $this->setArgument('tournament', $tournament);
    }
}
