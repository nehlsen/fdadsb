<?php

namespace Fda\TournamentBundle\Engine\Setup;

class PresetSimple
{
    public static function create($numberOfGroups)
    {
        $setup = new TournamentSetup();

        $seed = RoundSetupSeed::create($numberOfGroups); // create seed with n groups
        $seed->setAdvance(-1); // seed defaults to advance -1
        $setup->setSeed($seed);

        $groupRound = RoundSetupAva::createStraight(); // create an AvA round with straight input
        $groupRound->setAdvance(2); // each group, best 2 advance
        $setup->addRound($groupRound); // adding bottom first walking upwards

        $crisCrossRound = RoundSetupAva::createStep(1); // create an AvA round with step-1 input
        $crisCrossRound->setAdvance(1);
        $setup->addRound($crisCrossRound);

        $xRound = RoundSetupAva::createReduce(); // create an AvA round with reduce input
        $xRound->setAdvance(2);
        $setup->addRound($xRound);

        $yRound = RoundSetupAva::createSamePlace(); // create an AvA round with same-place input
        $yRound->setAdvance(2);
        $setup->addRound($yRound);

        $zRound = RoundSetupNull::createStack(); // create an Null round with stack input
        $zRound->setAdvance(-1); // null defaults to advance -1
        $setup->addRound($zRound);

        return $setup;
    }
}
