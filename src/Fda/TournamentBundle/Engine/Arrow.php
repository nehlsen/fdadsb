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

    public function __toString()
    {
        $label = '';

        if ($this->isDouble()) {
            $label .= 'D';
        } elseif ($this->isTriple()) {
            $label .= 'T';
        }

        $label .= $this->getScore();

        return $label;
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
     * @return bool
     */
    public function isSingle()
    {
        return $this->multiplier == Turn::MULTIPLIER_SINGLE;
    }

    /**
     * @return bool
     */
    public function isDouble()
    {
        return $this->multiplier == Turn::MULTIPLIER_DOUBLE;
    }

    /**
     * @return bool
     */
    public function isTriple()
    {
        return $this->multiplier == Turn::MULTIPLIER_TRIPLE;
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
