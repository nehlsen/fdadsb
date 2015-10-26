<?php

namespace Fda\TournamentBundle\Engine\Setup;

class InputStack implements InputInterface
{
    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'stack';
    }
}
