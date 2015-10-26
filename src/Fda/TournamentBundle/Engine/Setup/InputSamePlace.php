<?php

namespace Fda\TournamentBundle\Engine\Setup;

class InputSamePlace implements InputInterface
{
    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'same-place';
    }
}
