<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Engine\LeaderBoard\LeaderBoardEntryInterface;

class InputStack implements InputInterface
{
    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'stack';
    }

    /**
     * @inheritDoc
     */
    public function filter(RoundGearsInterface $previousRoundGears)
    {
        $advance = $previousRoundGears->getRound()->getSetup()->getAdvance(); // -1 = all
        $leaderBoardEntries = $previousRoundGears->getLeaderBoard()->getEntries($advance);

        $result = array(0 => array());
        foreach ($leaderBoardEntries as $groupNumber => $groupedEntries) {
            /** @var LeaderBoardEntryInterface[] $groupedEntries */
            foreach ($groupedEntries as $entry) {
                $result[0][] = $entry->getPlayer();
            }
        }

        return $result;
    }
}
