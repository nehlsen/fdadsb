<?php

namespace Fda\TournamentBundle\Tests\Engine\Setup;

use Fda\TournamentBundle\Engine\GameMode;
use Fda\TournamentBundle\Engine\LegMode;
use Fda\TournamentBundle\Engine\Setup\RoundSetupSeed;

class RoundSetupSeedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateNoGroups()
    {
        /*$seed = */RoundSetupSeed::create([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreate2EmptyGroups()
    {
        /*$seed = */RoundSetupSeed::create([[],[]]);
    }

    public function testCreate2GroupsNoProperties()
    {
        $seed = RoundSetupSeed::create([[1],[2]]);
        $this->assertNotNull($seed);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreate3Groups()
    {
        /*$seed = */RoundSetupSeed::create([[1], [2], [3]]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreatePlayerInMoreThanOneGroup()
    {
        /*$seed = */RoundSetupSeed::create([[1,2],[2,3]]);
    }

    public function testCreate2GroupsAndProperties()
    {
        $group0 = [1, 2];
        $group1 = [3, 4];
        $groups = [$group0, $group1];
        $seed = RoundSetupSeed::create($groups);

        $this->assertEquals('seed', $seed->getModeLabel());
        $this->assertEquals(2, $seed->getNumberOfGroups());
        $this->assertEquals($group0, $seed->getPlayerIds(0));
        $this->assertEquals($group1, $seed->getPlayerIds(1));
        $this->assertEquals($groups, $seed->getPlayerIdsGrouped());

        $this->assertEquals(-1, $seed->getAdvance());
        $this->assertNull($seed->getInput());
        $this->assertNull($seed->getGameMode());
        $this->assertNull($seed->getLegMode());
    }

    // ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
    // test some abstract-round-setup stuff

    public function testSetGetAdvance()
    {
        $seed = RoundSetupSeed::create([[1,2]]);

        $this->assertEquals(-1, $seed->getAdvance());

        $seed->setAdvance(5);
        $this->assertEquals(5, $seed->getAdvance());
    }

    public function testSetGetGameMode()
    {
        $seed = RoundSetupSeed::create([[1,2]]);

        $this->assertNull($seed->getGameMode());

        $mode = new GameMode(GameMode::FIRST_TO, 3);
        $seed->setGameMode($mode);
        $this->assertEquals($mode, $seed->getGameMode());
    }

    public function testSetGetLegMode()
    {
        $seed = RoundSetupSeed::create([[1,2]]);

        $this->assertNull($seed->getLegMode());

        $mode = new LegMode(LegMode::DOUBLE_OUT_301);
        $seed->setLegMode($mode);
        $this->assertEquals($mode, $seed->getLegMode());
    }
}
