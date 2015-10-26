<?php

namespace Fda\TournamentBundle\Engine\Setup;

class TournamentSetup implements TournamentSetupInterface
{
    /** @var RoundSetupSeedInterface */
    protected $seed;

    /** @var RoundSetupInterface[] */
    protected $rounds = array();

    /**
     * @inheritDoc
     */
    public function setSeed(RoundSetupSeedInterface $seedRound)
    {
        $this->seed = $seedRound;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * @inheritDoc
     */
    public function addRound(RoundSetupInterface $round)
    {
        $this->rounds[] = $round;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRounds()
    {
        return $this->rounds;
    }
}
