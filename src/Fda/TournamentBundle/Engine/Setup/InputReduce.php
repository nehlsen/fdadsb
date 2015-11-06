<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;

class InputReduce implements InputInterface
{
    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'reduce';
    }

    /**
     * @inheritDoc
     */
    public function filter(RoundGearsInterface $previousRoundGears)
    {
        // TODO implement input reduce : filter
        throw new \Exception('TODO');
    }
}
