<?php

namespace Fda\TournamentBundle\Engine\Setup;

class RoundSetupSeed extends AbstractRoundSetup implements RoundSetupSeedInterface
{
    /** @var int[] */
    protected $playerIdByGroup;

    /**
     * @param int[] $playerIdByGroup
     * @return RoundSetupSeed
     */
    public static function create($playerIdByGroup)
    {
        $groups = self::filterAndCheckPlayerIds($playerIdByGroup);
        if (!in_array(count($groups), array(1, 2, 4, 8))) {
            throw new \InvalidArgumentException('number of groups has to be 1, 2, 4 or 8');
        }

        // TODO: Implement create() method.
        $seed = new self();
        $seed->setAdvance(-1);
        $seed->playerIdByGroup = $playerIdByGroup;

        return $seed;
    }

    /**
     * remove empty groups, check if a player is in more than group
     *
     * @param int[][] $playerIdByGroup
     * @return int[][]
     * @throws \InvalidArgumentException if a player is found in more than one group
     * @throws \InvalidArgumentException if total number of players is less than two
     */
    protected static function filterAndCheckPlayerIds(array $playerIdByGroup)
    {
        $allPlayerIds = array();
        $groups = array();

        foreach ($playerIdByGroup as $players) {
            if (!is_array($players)) {
                throw new \InvalidArgumentException('expected array');
            }
            if (count($players) < 1) {
                continue;
            }

            $groupPlayers = array();
            foreach ($players as $playerId) {
                if (in_array($playerId, $allPlayerIds)) {
                    throw new \InvalidArgumentException('player in more than group');
                }

                $groupPlayers[] = $playerId;
                $allPlayerIds[] = $playerId;
            }

            $groupNumber = count($groups);
            $groups[$groupNumber] = $groupPlayers;
        }

        if (count($allPlayerIds) < 2) {
            throw new \InvalidArgumentException('a tournament with less than 2 players makes no sense');
        }

        return $groups;
    }

    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'seed';
    }

    /**
     * @inheritDoc
     */
    public function getNumberOfGroups()
    {
        return count($this->playerIdByGroup);
    }

    /**
     * @inheritDoc
     */
    public function getPlayerIds($group)
    {
        if (!array_key_exists($group, $this->playerIdByGroup)) {
            throw new \InvalidArgumentException();
        }

        return $this->playerIdByGroup[$group];
    }

    /**
     * @inheritDoc
     */
    public function getPlayerIdsGrouped()
    {
        return $this->playerIdByGroup;
    }
}
