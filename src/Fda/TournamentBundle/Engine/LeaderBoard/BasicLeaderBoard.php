<?php

namespace Fda\TournamentBundle\Engine\LeaderBoard;

use Fda\PlayerBundle\Entity\Player;

class BasicLeaderBoard implements LeaderBoardInterface
{
    /** @var BasicLeaderBoardEntry[][] */
    protected $groupedEntries = array();

    /** @var bool whether entries are sorted */
    protected $isDirty = false;

    /**
     * create a new entry or add points to an existing entry
     *
     * @param int    $groupNumber
     * @param Player $player
     * @param int    $points
     *
     * @return BasicLeaderBoardEntry
     */
    public function update($groupNumber, Player $player, $points)
    {
        $entry = $this->getEntry($groupNumber, $player);
        $entry->setPoints($entry->getPoints() + $points);

        $this->isDirty = true;

        return $entry;
    }

    /**
     * get entry, creates and adds the entry if not found
     *
     * @param int    $groupNumber
     * @param Player $player
     *
     * @return BasicLeaderBoardEntry
     */
    protected function getEntry($groupNumber, Player $player)
    {
        if (!array_key_exists($groupNumber, $this->groupedEntries)) {
            $this->groupedEntries[$groupNumber] = array();
        }

        $entry = null;
        foreach ($this->groupedEntries[$groupNumber] as $checkEntry) {
            if ($checkEntry->getPlayer()->getId() == $player->getId()) {
                $entry = $checkEntry;
                break;
            }
        }

        if (null === $entry) {
            $entry = BasicLeaderBoardEntry::create($player, 0);
            $this->groupedEntries[$groupNumber][] = $entry;
        }

        return $entry;
    }

    /**
     * @inheritDoc
     */
    public function getGroupNumbers()
    {
        $this->sortEntries();
        return array_keys($this->groupedEntries);
    }

    /**
     * @inheritDoc
     */
    public function getGroupEntries($groupNumber, $limit = null)
    {
        if (!array_key_exists($groupNumber, $this->groupedEntries)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid group number %d (valid:[%s])',
                $groupNumber,
                implode(',', $this->getGroupNumbers())
            ));
        }

        $this->sortEntries();

        $entries = $this->groupedEntries[$groupNumber];

        if (null === $limit || -1 == $limit) {
            return $entries;
        }
        if ($limit < 0) {
            throw new \InvalidArgumentException('can not fetch negative amount from list');
        }

        return array_slice($entries, 0, $limit, true);
    }

    /**
     * @inheritDoc
     */
    public function getEntries($limit = null)
    {
        $this->sortEntries();

        if (null === $limit || -1 == $limit) {
            return $this->groupedEntries;
        }

        $groupedEntries = array();
        foreach ($this->getGroupNumbers() as $groupNumber) {
            $groupedEntries[$groupNumber] = $this->getGroupEntries($groupNumber, $limit);
        }

        return $groupedEntries;
    }

    /**
     * sort the entries (if dirty is set)
     */
    protected function sortEntries()
    {
        if (!$this->isDirty) {
            return;
        }

        foreach (array_keys($this->groupedEntries) as $groupNumber) {
            usort(
                $this->groupedEntries[$groupNumber],
                function (LeaderBoardEntryInterface $entryA, LeaderBoardEntryInterface $entryB) {
                    return $this->entryComparator($entryA, $entryB);
                });
        }

        $this->isDirty = false;
    }

    /**
     * compare points, more points is better
     *
     * @param LeaderBoardEntryInterface $entry1
     * @param LeaderBoardEntryInterface $entry2
     *
     * @return int
     */
    protected function entryComparator(LeaderBoardEntryInterface $entry1, LeaderBoardEntryInterface $entry2)
    {
        if ($entry1->getPoints() < $entry2->getPoints()) {
            return 1;
        }
        if ($entry1->getPoints() > $entry2->getPoints()) {
            return -1;
        }
        return 0;
    }
}
