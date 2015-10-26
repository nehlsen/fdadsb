<?php

namespace Fda\TournamentBundle\Engine\Setup;

interface TournamentSetupInterface
{
    /**
     * set the seed round (has to be called prior to an addRound() call)
     *
     * @param RoundSetupSeedInterface $seedRound
     * @return TournamentSetupInterface this
     */
    public function setSeed(RoundSetupSeedInterface $seedRound);

    /**
     * @return RoundSetupSeedInterface
     */
    public function getSeed();

    /**
     * add a round (setSeed has to be called first)
     *
     * @param RoundSetupInterface $round
     * @return TournamentSetupInterface this
     */
    public function addRound(RoundSetupInterface $round);

    /**
     * get a list of configured rounds
     *
     * bottom up, first round is first round to play
     *
     * @return RoundSetupInterface[]
     */
    public function getRounds();
}
