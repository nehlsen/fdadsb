<?php

namespace Fda\TournamentBundle\Manager;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Arrow;
use Fda\TournamentBundle\Entity\Game;

class PlayerGameStatsFactory
{
    /** @var Player */
    protected $player;

    /** @var Game */
    protected $game;

    /** @var Arrow[] */
    protected $shots;

    /** @var PlayerGameStats */
    protected $stats;

    public static function create(Player $player, Game $game)
    {
        $factory = new self();
        $factory->player = $player;
        $factory->game = $game;
        $factory->stats = new PlayerGameStats();

        $factory->calculateStats();

        return $factory->stats;
    }

    protected function calculateStats()
    {
        $this->stats->legs = $this->game->getLegsWonOf($this->player);
        $this->analyzeLegs();
        $this->analyzeTurns();
        $this->analyzeShots();
    }

    protected function analyzeLegs()
    {
        $bestLegScore = 0;
        $legLeastShots = null;

        foreach ($this->game->getLegs() as $leg) {
            $score = $leg->getScoreOf($this->player);
            if ($score > $bestLegScore) {
                $bestLegScore = $score;
            }

            if ($leg->getWinner() == $this->player) {
                $shots = $leg->getCountShotsOf($this->player);
                if (null === $legLeastShots || $shots < $legLeastShots) {
                    $legLeastShots = $shots;
                }
            }
        }

        $this->stats->legBestScore = $bestLegScore;
        $this->stats->legLeastShots = $legLeastShots;
    }

    protected function analyzeTurns()
    {
        $turns = $this->game->getTurnsOf($this->player);

        $bestScore = 0;
        $bestAverage = 0.0;
        $totalShots = 0;
        $totalScore = 0;

        $this->shots = array();
        foreach ($turns as $turn) {
            if ($turn->isVoid()) {
                continue;
            }

            $this->shots = array_merge($this->shots, $turn->getArrows());

            if ($bestScore < $turn->getTotalScore()) {
                $bestScore = $turn->getTotalScore();
            }
            if ($bestAverage < $turn->getAverageScore()) {
                $bestAverage = $turn->getAverageScore();
            }

            $totalShots += 3;
            $totalScore += $turn->getTotalScore();
        }

        $overallAverage = count($turns) > 0 ? $totalScore / count($turns) : 0;

        $this->stats->turns = count($turns);
        $this->stats->turnBestAverage = $bestAverage;
        $this->stats->turnBestScore = $bestScore;
        $this->stats->turnAverage = $overallAverage;
    }

    protected function analyzeShots()
    {
        $this->stats->shots = 0;
        $this->stats->shotsZero = 0;
        $this->stats->shotsSingle = 0;
        $this->stats->shotsDouble = 0;
        $this->stats->shotsTriple = 0;
        $this->stats->shotsBest = 0;
        $this->stats->shotsAverage = 0;

        foreach ($this->shots as $shot) {
            $this->stats->shots += 1;

            $this->stats->shotsZero += $shot->getTotal() == 0 ? 1 : 0;
            $this->stats->shotsSingle += $shot->getTotal() != 0 && $shot->isSingle() ? 1 : 0;
            $this->stats->shotsDouble += $shot->getTotal() != 0 && $shot->isDouble() ? 1 : 0;
            $this->stats->shotsTriple += $shot->getTotal() != 0 && $shot->isTriple() ? 1 : 0;

            if ($shot->getTotal() > $this->stats->shotsBest) {
                $this->stats->shotsBest = $shot->getTotal();
            }

            $this->stats->totalScore += $shot->getTotal();
        }

        if ($this->stats->shots > 0) {
            $this->stats->shotsAverage = $this->stats->totalScore / $this->stats->shots;

            $this->stats->shotsZeroPercent = $this->stats->shotsZero / $this->stats->shots * 100.0;
            $this->stats->shotsSinglePercent = $this->stats->shotsSingle / $this->stats->shots * 100.0;
            $this->stats->shotsDoublePercent = $this->stats->shotsDouble / $this->stats->shots * 100.0;
            $this->stats->shotsTriplePercent = $this->stats->shotsTriple / $this->stats->shots * 100.0;
        } else {
            $this->stats->shotsAverage = 0;
            $this->stats->shotsZeroPercent = 0;
            $this->stats->shotsSinglePercent = 0;
            $this->stats->shotsDoublePercent = 0;
            $this->stats->shotsTriplePercent = 0;
        }
    }
}
