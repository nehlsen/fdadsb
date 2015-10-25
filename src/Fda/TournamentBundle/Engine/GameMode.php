<?php

namespace Fda\TournamentBundle\Engine;

/**
 * how to win a game
 */
final class GameMode
{
    /** first who wins 3 legs wins games */
    const FIRST_TO = 'first_to';

    /** first to have 3 more legs than contestant */
    const AHEAD    = 'ahead';

    private function __construct()
    {
    }
}
