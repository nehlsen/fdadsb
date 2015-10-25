<?php

namespace Fda\TournamentBundle\Engine\Setup;

/**
 * All vs. All
 */
class RoundSetupAva extends AbstractRoundSetup
{
    /**
     * @return RoundSetupAva
     */
    public static function createStraight()
    {
        // TODO: Implement createStraight() method.
        $setup = new self();
        $setup->setInput(new InputStraight());
        return $setup;
    }

    /**
     * @param int $steps
     * @return RoundSetupAva
     */
    public static function createStep($steps)
    {
        // TODO: Implement createStep() method.
    }

    /**
     * @return RoundSetupAva
     */
    public static function createReduce()
    {
        // TODO: Implement createReduce() method.
    }

    /**
     * @return RoundSetupAva
     */
    public static function createSamePlace()
    {
        // TODO: Implement createSamePlace() method.
    }

    /**
     * how many times each player has to play against each other player
     *
     * @param int $number
     * @return RoundSetupAva this
     */
    public function setNumberOfGames($number)
    {
    }
}
