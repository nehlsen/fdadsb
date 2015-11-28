<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Entity\Group;

class RoundGearsNull extends AbstractRoundGears
{
    /**
     * @inheritDoc
     */
    protected function initGameGearsForGroup(Group $group)
    {
        // TODO initGameGearsForGroup
        throw new \Exception('TODO initGameGearsForGroup');
    }

//    /**
//     * @inheritDoc
//     */
//    protected function handleGameCompleted(Game $game)
//    {
//        // TODO: Implement handleGameCompleted() method.
//        throw new \Exception('TODO: Implement handleGameCompleted() method.');
//    }
}
