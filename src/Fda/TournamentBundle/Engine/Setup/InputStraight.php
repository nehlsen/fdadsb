<?php

namespace Fda\TournamentBundle\Engine\Setup;

class InputStraight implements InputInterface
{
    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'straight';
    }
}
