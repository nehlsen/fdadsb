<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Engine\LeaderBoard\LeaderBoardEntryInterface;

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
        $leaderBoard = $previousRoundGears->getLeaderBoard();

        if (count($leaderBoard->getGroupNumbers()) % 2 != 0) {
            throw new \RuntimeException(
                'input reduce cuts the number of groups in half and can therefore only work with even group numbers.'
            );
        }

        $result = array();
        $newGroupCount = count($leaderBoard->getGroupNumbers())/2;
        for($n = 0; $n < $newGroupCount; ++$n) {
            $result[] = array();
        }

        // foreach group
        //  take first player and put into group%2
        //  repeat ... -> advance

        $remapGroupNumber = function ($groupNumber) use ($newGroupCount) {
            return $groupNumber%$newGroupCount;
        };

        $advance = $previousRoundGears->getRound()->getSetup()->getAdvance(); // -1 = all
        foreach ($leaderBoard->getEntries($advance) as $groupNumber => $groupedEntries) {
            /** @var LeaderBoardEntryInterface[] $groupedEntries */
            foreach ($groupedEntries as $entry) {
                $result[$remapGroupNumber($groupNumber)][] = $entry->getPlayer();
            }
        }

        return $result;
    }
}
