<?php

namespace Fda\TournamentBundle\Tests\Engine\Scoreboard;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Scoreboard\Scoreboard;

class ScoreboardTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $scoreboard = new Scoreboard();

        $this->assertEmpty($scoreboard->getGroupNumbers());
        $this->assertEmpty($scoreboard->getEntriesGrouped());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEmpty1()
    {
        $scoreboard = new Scoreboard();
        $scoreboard->getEntries(0);
    }

    public function test1Group2PlayersOrdered()
    {
        $scoreboard = new Scoreboard();
        $scoreboard->setEntry(0, new Player(), 2, false);
        $scoreboard->setEntry(0, new Player(), 1, false);

        $this->assertCount(1, $scoreboard->getGroupNumbers());
        $this->assertCount(1, $scoreboard->getEntriesGrouped());

        $this->assertArrayHasKey(0, $scoreboard->getEntriesGrouped());
        $this->assertCount(2, $scoreboard->getEntries(0));

        $this->assertEquals(2, $scoreboard->getEntries(0)[0]->getScore());
        $this->assertEquals(1, $scoreboard->getEntries(0)[1]->getScore());
    }

    public function test1Group2PlayersNotOrdered()
    {
        $scoreboard = new Scoreboard();
        $scoreboard->setEntry(0, new Player(), 1, false);
        $scoreboard->setEntry(0, new Player(), 2, false);

        $this->assertCount(1, $scoreboard->getGroupNumbers());
        $this->assertCount(1, $scoreboard->getEntriesGrouped());

        $this->assertArrayHasKey(0, $scoreboard->getEntriesGrouped());
        $this->assertCount(2, $scoreboard->getEntries(0));

        $this->assertEquals(2, $scoreboard->getEntries(0)[0]->getScore());
        $this->assertEquals(1, $scoreboard->getEntries(0)[1]->getScore());
    }

    public function test1Group2PlayersEqual()
    {
        $scoreboard = new Scoreboard();
        $scoreboard->setEntry(0, new Player(), 1, false);
        $scoreboard->setEntry(0, new Player(), 1, false);

        $this->assertCount(1, $scoreboard->getGroupNumbers());
        $this->assertCount(1, $scoreboard->getEntriesGrouped());

        $this->assertArrayHasKey(0, $scoreboard->getEntriesGrouped());
        $this->assertCount(2, $scoreboard->getEntries(0));

        $this->assertEquals(1, $scoreboard->getEntries(0)[0]->getScore());
        $this->assertEquals(1, $scoreboard->getEntries(0)[1]->getScore());
    }

    public function test2GroupsSamePlayers()
    {
        // put one and the same player in two different groups
        // not sure if it should be allowed
        //  ATW it is possible

        $scoreboard = new Scoreboard();
        $player1 = new Player();
        $scoreboard->setEntry(0, $player1, 1, false);
        $scoreboard->setEntry(0, $player1, 2, false);

        $this->assertCount(1, $scoreboard->getGroupNumbers());
        $this->assertCount(1, $scoreboard->getEntriesGrouped());

        $this->assertArrayHasKey(0, $scoreboard->getEntriesGrouped());
        $this->assertCount(2, $scoreboard->getEntries(0));

        $this->assertEquals(2, $scoreboard->getEntries(0)[0]->getScore());
        $this->assertEquals(1, $scoreboard->getEntries(0)[1]->getScore());
    }
}
