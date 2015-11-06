<?php

namespace Fda\TournamentBundle\Tests\Engine;

use Fda\TournamentBundle\Engine\Bolts\GameMode;

class GameModeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateValid()
    {
        $gm1 = new GameMode(GameMode::FIRST_TO, 1);
        $this->assertEquals(GameMode::FIRST_TO, $gm1->getMode());
        $this->assertEquals(1, $gm1->getCount());

        $gm2 = new GameMode(GameMode::FIRST_TO, 3);
        $this->assertEquals(GameMode::FIRST_TO, $gm2->getMode());
        $this->assertEquals(3, $gm2->getCount());

        $gm3 = new GameMode(GameMode::AHEAD, 1);
        $this->assertEquals(GameMode::AHEAD, $gm3->getMode());
        $this->assertEquals(1, $gm3->getCount());

        $gm4 = new GameMode(GameMode::AHEAD, 3);
        $this->assertEquals(GameMode::AHEAD, $gm4->getMode());
        $this->assertEquals(3, $gm4->getCount());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidMode()
    {
        new GameMode('invalid_mode', 3);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateZeroCount()
    {
        new GameMode(GameMode::AHEAD, 0);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateNegativeCount()
    {
        new GameMode(GameMode::AHEAD, -1);
    }
}
