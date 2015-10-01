<?php

namespace Fda\TournamentBundle\Manager;
use Fda\PlayerBundle\Entity\Player;

/**
 * statistics for ONE Player and ONE Game
 */
class PlayerGameStats
{
    /** @var Player */
    public $player;

    /** @var int legs won (not played, as both players play the same amount) */
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

    /** @var double total misses (zeros) in percent */
    public $shotsZeroPercent;
    /** @var double total singles (excluding zeros) in percent */
    public $shotsSinglePercent;
    /** @var double total doubles in percent */
    public $shotsDoublePercent;
    /** @var double total triples in percent */
    public $shotsTriplePercent;
}
