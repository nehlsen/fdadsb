<?php

namespace Fda\TournamentBundle\Engine\LeaderBoard;

interface LeaderBoardInterface
{
    /**
     * get all group numbers
     *
     * @return int[]
     */
    public function getGroupNumbers();

    /**
     * get all entries for specified group
     *
     * @param int      $groupNumber
     * @param int|null $limit       get only the best limit players, null all
     *
     * @return LeaderBoardEntryInterface[]
     */
    public function getGroupEntries($groupNumber, $limit = null);

    /**
     * get all entries grouped by group
     *
     * @param int|null $limit get only the best limit players, null all
     *
     * @return LeaderBoardEntryInterface[][]
     */
    public function getEntries($limit = null);
}
