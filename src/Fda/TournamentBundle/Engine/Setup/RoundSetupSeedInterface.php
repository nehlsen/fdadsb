<?php

namespace Fda\TournamentBundle\Engine\Setup;

interface RoundSetupSeedInterface
{
    /**
     * get number of groups
     *
     * @return int
     */
    public function getNumberOfGroups();

    /**
     * get IDs of players in specified group
     *
     * @param int $group
     * @return int[]
     */
    public function getPlayerIds($group);

    /**
     * get all player-IDs by group
     * @return int[][]
     */
    public function getPlayerIdsGrouped();
}
