<?php

namespace Fda\TournamentBundle\Manager;

use Fda\TournamentBundle\Entity\Game;

class GameStatsFactory
{
    public static function create(Game $game)
    {
        $stats = new GameStats();
        $stats->player1 = PlayerGameStatsFactory::create($game->getPlayer1(), $game);
        $stats->player2 = PlayerGameStatsFactory::create($game->getPlayer2(), $game);

        $stats->legs = $stats->player1->legs + $stats->player2->legs;
        $stats->legBestScore = max($stats->player1->legBestScore, $stats->player2->legBestScore);
        $stats->legLeastShots = min($stats->player1->legLeastShots, $stats->player2->legLeastShots);
        $stats->turns = $stats->player1->turns + $stats->player2->turns;

        $stats->turnBestScore = max($stats->player1->turnBestScore, $stats->player2->turnBestScore);
        $stats->turnBestAverage = max($stats->player1->turnBestAverage, $stats->player2->turnBestAverage);
        $stats->turnAverage = max($stats->player1->turnAverage, $stats->player2->turnAverage);

        $stats->shots = $stats->player1->shots + $stats->player2->shots;
        $stats->shotsZero = $stats->player1->shotsZero + $stats->player2->shotsZero;
        $stats->shotsSingle = $stats->player1->shotsSingle + $stats->player2->shotsSingle;
        $stats->shotsDouble = $stats->player1->shotsDouble + $stats->player2->shotsDouble;
        $stats->shotsTriple = $stats->player1->shotsTriple + $stats->player2->shotsTriple;

        $stats->shotsBest = max($stats->player1->shotsBest, $stats->player2->shotsBest);
        $stats->shotsAverage = max($stats->player1->shotsAverage, $stats->player2->shotsAverage);
        $stats->totalScore = max($stats->player1->totalScore, $stats->player2->totalScore);

        return $stats;
    }
}
