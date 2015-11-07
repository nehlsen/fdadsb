<?php

namespace Fda\TournamentBundle\Engine\Bolts;

class InvalidArrowException extends \RuntimeException
{
    public static function tripleBullsEye()
    {
        return new self(
            'bulls-eye can not be tripled!'
        );
    }

    public static function multipliedZero()
    {
        return new self(
            'zero can not be doubled or tripled!'
        );
    }

    public static function invalidArrowNumber($number)
    {
        return new self(sprintf(
            '"%d" is not a valid arrow-number (valid numbers are 1, 2, 3)', $number
        ));
    }

    public static function invalidScore($score)
    {
        return new self(sprintf(
            '"%d" is not a valid score', $score
        ));
    }

    public static function invalidMultiplier($multiplier)
    {
        return new self(sprintf(
            '"%s" is not a valid multiplier', $multiplier
        ));
    }
}
