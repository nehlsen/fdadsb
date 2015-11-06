<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;

class InputSamePlace implements InputInterface
{
    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'same-place';
    }

    /**
     * @inheritDoc
     */
    public function filter(RoundGearsInterface $previousRoundGears)
    {
        // TODO implement input same-place : filter
        throw new \Exception('TODO');
    }
}
