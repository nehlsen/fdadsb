<?php

namespace Fda\TournamentBundle\Engine;

class Arrow
{
    const MULTIPLIER_SINGLE = 'single';
    const MULTIPLIER_DOUBLE = 'double';
    const MULTIPLIER_TRIPLE = 'triple';

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
        $this->number = (int)$number;
        $this->score = (int)$score;
        $this->multiplier = (string)$multiplier;

        $this->check();
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
        return $this->multiplier == self::MULTIPLIER_SINGLE;
    }

    /**
     * @return bool
     */
    public function isDouble()
    {
        return $this->multiplier == self::MULTIPLIER_DOUBLE;
    }

    /**
     * @return bool
     */
    public function isTriple()
    {
        return $this->multiplier == self::MULTIPLIER_TRIPLE;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        switch ($this->multiplier) {
            case self::MULTIPLIER_DOUBLE;
                return $this->score * 2;
                break;
            case self::MULTIPLIER_TRIPLE;
                return $this->score * 3;
                break;
        }

        return $this->score;
    }

    protected function check()
    {
        $this->checkNumber();
        $this->checkScore();
        $this->checkMultiplier();

        if ($this->getScore() == 25 && $this->getMultiplier() == self::MULTIPLIER_TRIPLE) {
            throw new \RuntimeException('bulls-eye can not be tripled!');
        }
    }

    protected function checkNumber()
    {
        if (!in_array($this->getNumber(), [1,2,3])) {
            throw new \RuntimeException(sprintf('"%d" is not a valid arrow-number', $this->getNumber()));
        }
    }

    protected function checkScore()
    {
        $validScores = array(
            25, 20, 19,
            18, 17, 16,
            15, 14, 13,
            12, 11, 10,
            9,  8,  7,
            6,  5,  4,
            3,  2,  1,
            0
        );

        if (!in_array($this->getScore(), $validScores)) {
            throw new \RuntimeException(sprintf('"%d" is not a valid score', $this->getScore()));
        }
    }

    protected function checkMultiplier()
    {
        $validMultipliers = array(
            self::MULTIPLIER_SINGLE,
            self::MULTIPLIER_DOUBLE,
            self::MULTIPLIER_TRIPLE,
        );

        if (!in_array($this->getMultiplier(), $validMultipliers)) {
            throw new \RuntimeException(sprintf('"%s" is not a valid multiplier', $this->getMultiplier()));
        }
    }
}
