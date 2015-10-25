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
        if (!in_array(count($playerIdByGroup), array(2,4,8))) {
            throw new \InvalidArgumentException('number of groups has to be 2, 4 or 8');
        }

        // TODO: Implement create() method.
        $seed = new self();
        $seed->setAdvance(-1);
        $seed->playerIdByGroup = $playerIdByGroup;

        return $seed;
    }

    // TODO move to interface?
    public function getNumberOfGroups()
    {
        return count($this->playerIdByGroup);
    }

    // TODO move to interface?
    public function getPlayerIds($group)
    {
        if (!array_key_exists($group, $this->playerIdByGroup)) {
            throw new \InvalidArgumentException();
        }

        return $this->playerIdByGroup[$group];
    }
}
