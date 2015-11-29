<?php

namespace Fda\TournamentBundle\Tests\Engine\LeaderBoard;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\LeaderBoard\BasicLeaderBoardEntry;

class BasicLeaderBoardEntryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $entry = new BasicLeaderBoardEntry();

        $this->assertNull($entry->getPlayer());
        $this->assertNull($entry->getPoints());
        $this->assertNull($entry->isFinal());
    }

    public function testFactory()
    {
        $aPlayer = new Player();
        $herPoints = 47;

        $entry = BasicLeaderBoardEntry::create($aPlayer, $herPoints); // assumes is-final:true
        $this->assertEquals($aPlayer, $entry->getPlayer());
        $this->assertEquals($herPoints, $entry->getPoints());
        $this->assertTrue($entry->isFinal());

        $entry1 = BasicLeaderBoardEntry::create($aPlayer, $herPoints, true);
        $this->assertTrue($entry1->isFinal());

        $entry2 = BasicLeaderBoardEntry::create($aPlayer, $herPoints, false);
        $this->assertFalse($entry2->isFinal());
    }

    public function testSetterGetter()
    {
        $entry = BasicLeaderBoardEntry::create(new Player(), 47);

        $this->assertEquals(47, $entry->getPoints());
        $entry->setPoints(123);
        $this->assertEquals(123, $entry->getPoints());

        // although this is not intentional, the current implementation allows it...
        $entry->setPoints(-1);
        $this->assertEquals(-1, $entry->getPoints());
        $entry->setPoints(null);
        $this->assertEquals(null, $entry->getPoints());
        $entry->setPoints('ten points');
        $this->assertEquals('ten points', $entry->getPoints());

        // ---

        $this->assertTrue($entry->isFinal());
        $entry->setFinal(false);
        $this->assertFalse($entry->isFinal());

        // although this is not intentional, the current implementation allows it...
        $entry->setFinal(-1);
        $this->assertEquals(-1, $entry->isFinal());
        $entry->setFinal(null);
        $this->assertEquals(null, $entry->isFinal());
        $entry->setFinal('not done yet');
        $this->assertEquals('not done yet', $entry->isFinal());
    }
}
