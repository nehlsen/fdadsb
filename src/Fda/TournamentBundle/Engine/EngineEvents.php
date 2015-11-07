<?php

namespace Fda\TournamentBundle\Engine;

final class EngineEvents
{
    /**
     * this event is emitted by ledger on incoming arrow
     * the arrow has does not yet have a number assigned
     * event type: ArrowEvent
     */
    const ARROW_INCOMING = 'fda.tournament.arrow.incoming';

    /**
     * this event ist emitted after the Arrow has been registered by the Turn
     * arrow number is now set
     * event type: ArrowEvent
     */
    const ARROW_REGISTERED = 'fda.tournament.arrow.registered';

    // three arrows or a bust
    const TURN_COMPLETED = '';

    const LEG_COMPLETED = '';
    const GAME_COMPLETED = '';
    const ROUND_COMPLETED = '';
}
