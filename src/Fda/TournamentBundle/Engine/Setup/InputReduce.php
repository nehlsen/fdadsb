<?php

namespace Fda\TournamentBundle\Engine\Setup;

class InputReduce implements InputInterface
{
    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'reduce';
    }
}
