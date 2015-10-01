<?php

namespace Fda\TournamentBundle\Manager;

class GameStats
{
    /** @var PlayerGameStats */
    public $player1;
    /** @var PlayerGameStats */
    public $player2;

    /** @var int total number of legs played */
    public $legs;

    /** @var int score of best leg by score (if at least one leg is won, this is guaranteed to be 301/501) */
    public $legBestScore;

    /** @var int shots of best leg by least shots */
    public $legLeastShots;

    /** @var int turns played */
    public $turns;

    /** @var int best score in one turn */
    public $turnBestScore;

    /** @var double best average score in one turn */
    public $turnBestAverage;

    /** @var double overall average score per turn */
    public $turnAverage;

    /** @var int total shots fired */
    public $shots;
    /** @var int total misses (zeros) */
    public $shotsZero;
    /** @var int total singles (excluding zeros) */
    public $shotsSingle;
    /** @var int total doubles */
    public $shotsDouble;
    /** @var int total triples */
    public $shotsTriple;
    /** @var int score of best shot */
    public $shotsBest;
    /** @var double average score per shot */
    public $shotsAverage;
    /** @var int total score this game */
    public $totalScore;
}
