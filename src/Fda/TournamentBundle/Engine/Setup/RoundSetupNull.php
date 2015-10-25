<?php

namespace Fda\TournamentBundle\Engine\Setup;

class RoundSetupNull extends AbstractRoundSetup
{
    /**
     * @return RoundSetupNull
     */
    public static function createStack()
    {
        // TODO: Implement createStack() method.
        $setup = new self();
        $setup->setInput(new InputStack());
        return $setup;
    }
}
