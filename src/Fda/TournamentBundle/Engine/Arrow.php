<?php

namespace Fda\TournamentBundle\Engine;

use Fda\TournamentBundle\Entity\Turn;

class Arrow
{
    /** @var int */
    protected $number;
    /** @var int */
    protected $score;
    /** @var string */
    protected $multiplier;

    /**
     * Arrow constructor.
     * @param int    $number
     * @param int    $score
     * @param string $multiplier
     */
    public function __construct($number, $score, $multiplier)
    {
        $this->number = $number;
        $this->score = $score;
        $this->multiplier = $multiplier;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return mixed
     */
    public function getMultiplier()
    {
        return $this->multiplier;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        switch ($this->multiplier) {
            case Turn::MULTIPLIER_SINGLE;
                return $this->score * 1;
                break;
            case Turn::MULTIPLIER_DOUBLE;
                return $this->score * 2;
                break;
            case Turn::MULTIPLIER_TRIPLE;
                return $this->score * 3;
                break;
        }

        return 0;
    }
}
