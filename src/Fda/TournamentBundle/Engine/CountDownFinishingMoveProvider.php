<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Entity\Turn;

class CountDownFinishingMoveProvider
{
    /** @var int */
    protected $remainingScore;

    /** @var bool */
    protected $isDoubleOutRequired;

    /** @var Arrow[] score=>Arrow */
    protected $possibleShots;

    /**
     * @param int  $remainingScore
     * @param bool $isDoubleOutRequired
     */
    public function __construct($remainingScore, $isDoubleOutRequired)
    {
        $this->remainingScore = $remainingScore;
        $this->isDoubleOutRequired = $isDoubleOutRequired;
        $this->possibleShots = $this->calculatePossibleShots();
    }

    /**
     * get a list of possible moves to end the game
     *
     * @return Arrow[]
     */
    public function getFinishingMoves()
    {
        $arrows = array();

        $remainingScore = $this->remainingScore;
        if ($this->isDoubleOutRequired) {
            $shot = $this->getBestDoubleBelow($remainingScore);
            $remainingScore -= $shot->getTotal();
            $arrows[] = $shot;
        }

        while ($remainingScore > 0) {
            $shot = $this->getBestShotBelow($remainingScore);
            $remainingScore -= $shot->getTotal();
            $arrows[] = $shot;
        }

        $arrows = array_reverse($arrows);

        return $arrows;
    }

    /**
     * get the best shot less or equal to score
     *
     * @param int $desiredScore
     * @return Arrow
     * @throws \Exception
     */
    protected function getBestShotBelow($desiredScore)
    {
        foreach ($this->possibleShots as $score => $arrow) {
            if ($desiredScore >= $score) {
                return $arrow;
            }
        }

        throw new \Exception();
    }

    /**
     * get the best double less or equal to score
     *
     * @param int $desiredScore
     * @return Arrow
     * @throws \Exception
     */
    protected function getBestDoubleBelow($desiredScore)
    {
        foreach ($this->possibleShots as $score => $arrow) {
            if (!$arrow->isDouble()) {
                continue;
            }

            if ($desiredScore >= $score) {
                return $arrow;
            }
        }

        throw new \Exception();
    }

    protected function calculatePossibleShots()
    {
        $convertMultiplier = function ($multiplier) {
            switch ($multiplier) {
                case 3:
                    return Turn::MULTIPLIER_TRIPLE;
                case 2:
                    return Turn::MULTIPLIER_DOUBLE;
            }

            return Turn::MULTIPLIER_SINGLE;
        };

        $scores = array(25);
        for ($score = 20; $score > 0; --$score) {
            $scores[] = $score;
        }

        // score => Arrow
        $possibleShots = array();
        foreach ($scores as $score) {
            for ($multiplier = 3; $multiplier > 0; --$multiplier) {
                if ($score == 25 && $multiplier == 3) {
                    // can't be done ;)
                    continue;
                }

                $arrow = new Arrow(
                    1,
                    $score,
                    $convertMultiplier($multiplier)
                );

                if ($multiplier > 1 && array_key_exists($arrow->getTotal(), $possibleShots)) {
                    // skip, doubles and triples are harder than singles
                    continue;
                }
                $possibleShots[$arrow->getTotal()] = $arrow;
            }
        }

        krsort($possibleShots);

        return $possibleShots;
    }
}
