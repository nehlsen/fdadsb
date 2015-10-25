<?php

namespace Fda\TournamentBundle\Tests\Engine\Setup;

use Fda\TournamentBundle\Engine\Setup\RoundSetupAva;
use Fda\TournamentBundle\Engine\Setup\RoundSetupBvw;
use Fda\TournamentBundle\Engine\Setup\RoundSetupNull;
use Fda\TournamentBundle\Engine\Setup\RoundSetupSeed;
use Fda\TournamentBundle\Engine\Setup\TournamentSetup;

class TestSetup
{
    public function testAllVsAll()
    {
        $setup = new TournamentSetup();

        $seed = RoundSetupSeed::create(1); // create seed with 1 group
        $seed->setAdvance(-1); // seed defaults to advance -1
        $setup->setSeed($seed);

        $groupRound = RoundSetupAva::createStraight(); // create an AvA round with straight input
        $groupRound->setAdvance(-1); // everybody advances
        $setup->addRound($groupRound); // adding bottom first walking upwards
    }

    public function testAllVsAll2Round()
    {
        // hin- und rueckrunde
        $setup = new TournamentSetup();

        $seed = RoundSetupSeed::create(1); // create seed with 1 group
        $seed->setAdvance(-1); // seed defaults to advance -1
        $setup->setSeed($seed);

        $groupRound = RoundSetupAva::createStraight(); // create an AvA round with straight input
        $groupRound->setAdvance(-1); // everybody advances
        $groupRound->setNumberOfGames(2);
        $setup->addRound($groupRound); // adding bottom first walking upwards
    }

    public function testSimpleTournament()
    {
        $setup = new TournamentSetup();

        $seed = RoundSetupSeed::create(4); // create seed with 4 groups
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
    }

    public function testFullTournament()
    {
        $setup = new TournamentSetup();

        $seed = RoundSetupSeed::create(4); // create seed with 4 groups
        $seed->setAdvance(-1); // seed defaults to advance -1
        $setup->setSeed($seed);

        $groupRound = RoundSetupAva::createStraight(); // create an AvA round with straight input
        $groupRound->setAdvance(4); // each group, best 4 advance
        $setup->addRound($groupRound); // adding bottom first walking upwards

        $knockOutRound = RoundSetupBvw::createStep(1); // create an BvW round with step-1 input
        $knockOutRound->setAdvance(2); // each group, best 2 advance
        $setup->addRound($knockOutRound);

        $crisCrossRound = RoundSetupAva::createStep(2); // create an AvA round with step-2 input
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
    }
}
