<?php

namespace Fda\TournamentBundle\Engine\Scoreboard;

use Fda\PlayerBundle\Entity\Player;

class Scoreboard
{
    /** @var ScoreboardEntry[][] */
    protected $groupedEntries = array();

    /**
     * @param int    $groupNumber
     * @param Player $player
     * @param int    $score
     * @param bool   $isFinal
     */
    public function setEntry($groupNumber, Player $player, $score, $isFinal)
    {
        if (!array_key_exists($groupNumber, $this->groupedEntries)) {
            $this->groupedEntries[$groupNumber] = array();
        }

        $this->groupedEntries[$groupNumber][] = new ScoreboardEntry($player, $score, $isFinal);

        usort($this->groupedEntries[$groupNumber], function (ScoreboardEntry $entryA, ScoreboardEntry $entryB) {
            if ($entryA->getScore() < $entryB->getScore()) {
                return 1;
            }
            if ($entryA->getScore() > $entryB->getScore()) {
                return -1;
            }
            return 0;
        });
    }

    /**
     * get all group numbers
     *
     * @return int[]
     */
    public function getGroupNumbers()
    {
        return array_keys($this->groupedEntries);
    }

    /**
     * get all entries for specified group
     *
     * @param int $groupNumber
     *
     * @return ScoreboardEntry[]
     */
    public function getEntries($groupNumber)
    {
        if (!array_key_exists($groupNumber, $this->groupedEntries)) {
            throw new \InvalidArgumentException();
        }

        return $this->groupedEntries[$groupNumber];
    }

    /**
     * get all entries grouped by group
     *
     * @return ScoreboardEntry[][]
     */
    public function getEntriesGrouped()
    {
        return $this->groupedEntries;
    }
}
