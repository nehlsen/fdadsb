<?php

namespace Fda\TournamentBundle\Engine\Bolts;

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
        if ($remainingScore < 0) {
            throw new \InvalidArgumentException(sprintf(
                'remaining score has to be a positive number greater or equal to 0, provided: %d',
                $remainingScore
            ));
        }

        $this->remainingScore = $remainingScore;
        $this->isDoubleOutRequired = $isDoubleOutRequired;
        $this->possibleShots = $this->calculatePossibleShots();
    }

    /**
     * get a list of possible moves to end the game
     *
     * an empty list is returned if the remaining score is 0 or not reachable
     * e.g. remaining score is 1 and double out is required. that ain't not possible!
     *
     * @return Arrow[]
     */
    public function getFinishingMoves()
    {
        $arrows = array();

        if ($this->remainingScore < 1) {
            return $arrows;
        }
        if ($this->remainingScore == 1 && $this->isDoubleOutRequired) {
            return $arrows;
        }

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

        throw new \Exception(sprintf(
            'failed to find a double-shot below %d',
            $desiredScore
        ));
    }

    protected function calculatePossibleShots()
    {
        $convertMultiplier = function ($multiplier) {
            switch ($multiplier) {
                case 3:
                    return Arrow::MULTIPLIER_TRIPLE;
                case 2:
                    return Arrow::MULTIPLIER_DOUBLE;
            }

            return Arrow::MULTIPLIER_SINGLE;
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

                $arrow = Arrow::create(
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
