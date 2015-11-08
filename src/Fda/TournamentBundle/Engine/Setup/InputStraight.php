<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Engine\LeaderBoard\LeaderBoardEntryInterface;

// round-setup:advance players from each group will advance
//  -1 ==> all players
// straight input replicates the groups
class InputStraight implements InputInterface
{
    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'straight';
    }

    /**
     * @inheritDoc
     */
    public function filter(RoundGearsInterface $previousRoundGears)
    {
        if (!$previousRoundGears->isRoundClosed()) {
            throw new \RuntimeException();
        }

        $advance = $previousRoundGears->getSetup()->getAdvance();
        $leaderBoard = $previousRoundGears->getLeaderBoard();

        $output = array();
        foreach ($leaderBoard->getEntries() as $groupNumber => $entries) {
            /** @var LeaderBoardEntryInterface[] $entries */
            $output[$groupNumber] = array();
            foreach ($entries as $entry) {
                if (-1 != $advance && count($output[$groupNumber]) >= $advance) {
                    break;
                }

                $output[$groupNumber][] = $entry->getPlayer();
            }
        }

        return $output;
    }
}
