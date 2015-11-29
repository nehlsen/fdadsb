<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Engine\LeaderBoard\LeaderBoardEntryInterface;

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
        // create one group for all 1st
        // create one group for all 2nd
        // ...
        // until reached advance

        $advance = $previousRoundGears->getRound()->getSetup()->getAdvance(); // -1 = all
        $leaderBoardEntries = $previousRoundGears->getLeaderBoard()->getEntries($advance);

        $result = array();
        foreach ($leaderBoardEntries as $groupNumber => $groupedEntries) {
            /** @var LeaderBoardEntryInterface[] $groupedEntries */
            foreach ($groupedEntries as $place0 => $entry) {
                $result[$place0][] = $entry->getPlayer();
            }
        }

        return $result;
    }
}
