<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Bolts\GameMode;
use Fda\TournamentBundle\Engine\Bolts\LegMode;

class PresetSimple
{
    public static function create($playerIdByGroup)
    {
        $setup = new TournamentSetup();

        $seed = RoundSetupSeed::create($playerIdByGroup); // create seed with n groups
        $seed->setAdvance(-1); // seed defaults to advance -1
        $setup->setSeed($seed);

        $groupRound = RoundSetupAva::createStraight(); // create an AvA round with straight input
        $groupRound->setAdvance(2); // each group, best 2 advance
        $groupRound->setGameMode(new GameMode(GameMode::FIRST_TO, 3));
        $groupRound->setLegMode(new LegMode(LegMode::SINGLE_OUT_301));
        $setup->addRound($groupRound); // adding bottom first walking upwards

        $crisCrossRound = RoundSetupAva::createStep(1); // create an AvA round with step-1 input
        $crisCrossRound->setAdvance(1);
        $crisCrossRound->setGameMode(new GameMode(GameMode::FIRST_TO, 3));
        $crisCrossRound->setLegMode(new LegMode(LegMode::SINGLE_OUT_301));
        $setup->addRound($crisCrossRound);

        $xRound = RoundSetupAva::createReduce(); // create an AvA round with reduce input
        $xRound->setAdvance(2);
        $xRound->setGameMode(new GameMode(GameMode::FIRST_TO, 3));
        $xRound->setLegMode(new LegMode(LegMode::SINGLE_OUT_301));
        $setup->addRound($xRound);

        $yRound = RoundSetupAva::createSamePlace(); // create an AvA round with same-place input
        $yRound->setAdvance(2);
        $yRound->setGameMode(new GameMode(GameMode::FIRST_TO, 3));
        $yRound->setLegMode(new LegMode(LegMode::SINGLE_OUT_301));
        $setup->addRound($yRound);

        $zRound = RoundSetupNull::createStack(); // create an Null round with stack input
        $zRound->setAdvance(-1); // null defaults to advance -1
        $setup->addRound($zRound);

        return $setup;
    }
}
