<?php

namespace Fda\TournamentBundle\Engine\Setup;

class RoundSetupBvw extends AbstractRoundSetup
{
    /**
     * @param int $steps
     * @return RoundSetupBvw
     */
    public static function createStep($steps)
    {
        // TODO: Implement createStep() method.
    }

    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'bvw';
    }
}
