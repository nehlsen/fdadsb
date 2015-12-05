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

    /**
     * this event is emitted after a turn has been completed
     * a turn is completed by either being a bust or having 3 arrows
     * event type: TurnEvent
     */
    const TURN_COMPLETED = 'fda.tournament.turn.completed';

    /**
     * this event is emitted after a leg has been completed
     * event type: LegEvent
     */
    const LEG_COMPLETED = 'fda.tournament.leg.completed';

    /**
     * this event is emitted after a game has been completed
     * event type: GameEvent
     */
    const GAME_COMPLETED = 'fda.tournament.game.completed';

    /**
     * this event is emitted after a group has been completed
     * a group is completed when all games in the group are completed
     *
     * event type: GroupEvent
     */
    const GROUP_COMPLETED = 'fda.tournament.group.completed';

    /**
     * this event is emitted after a round has been completed
     * a round is completed when the last game of the round has been completed
     *
     * event type: RoundEvent
     */
    const ROUND_COMPLETED = 'fda.tournament.round.completed';

    /**
     * this event is emitted when the last game of the tournament is finished
     *
     * event type: TournamentEvent
     */
    const TOURNAMENT_COMPLETED = 'fda.tournament.tournament.completed';
}
