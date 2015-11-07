<?php

namespace Fda\TournamentBundle\Engine\Bolts;

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

    protected function __construct()
    {
    }

    /**
     * create a new arrow
     *
     * @param int $score
     * @param int $multiplier
     *
     * @return Arrow
     *
     * @throws InvalidArrowException
     */
    public static function create($score, $multiplier)
    {
        $arrow = new self();
        $arrow->score = $score;
        $arrow->multiplier = $multiplier;

        $arrow->checkScore();
        $arrow->checkMultiplier();
        $arrow->checkCombination();

        return $arrow;
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
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
        $this->checkNumber();
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return string one of Arrow::MULTIPLIER_*
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

    /**
     * check if the arrow number is valid
     * @throws InvalidArrowException
     */
    protected function checkNumber()
    {
        if (!in_array($this->getNumber(), [1,2,3])) {
            throw InvalidArrowException::invalidArrowNumber($this->getNumber());
        }
    }

    /**
     * check if score is valid
     * @throws InvalidArrowException
     */
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
            throw InvalidArrowException::invalidScore($this->getScore());
        }
    }

    /**
     * check if multiplier is valid
     * @throws InvalidArrowException
     */
    protected function checkMultiplier()
    {
        $validMultipliers = array(
            self::MULTIPLIER_SINGLE,
            self::MULTIPLIER_DOUBLE,
            self::MULTIPLIER_TRIPLE,
        );

        if (!in_array($this->getMultiplier(), $validMultipliers)) {
            throw InvalidArrowException::invalidMultiplier($this->getMultiplier());
        }
    }

    /**
     * check if multiplier makes sense in combination with score
     * @throws InvalidArrowException
     */
    protected function checkCombination()
    {
        if ($this->getScore() == 25 && $this->getMultiplier() == self::MULTIPLIER_TRIPLE) {
            throw InvalidArrowException::tripleBullsEye();
        }

        if ($this->getScore() == 0 && $this->getMultiplier() != self::MULTIPLIER_SINGLE) {
            throw InvalidArrowException::multipliedZero();
        }
    }
}
