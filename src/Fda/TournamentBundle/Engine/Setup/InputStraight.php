<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Engine\Scoreboard\ScoreboardEntry;

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
        $scoreboard = $previousRoundGears->getScoreboard();

        $output = array();
        foreach ($scoreboard->getEntriesGrouped() as $groupNumber => $entries) {
            /** @var ScoreboardEntry[] $entries */
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
