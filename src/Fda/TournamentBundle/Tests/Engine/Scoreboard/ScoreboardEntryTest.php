<?php

namespace Fda\TournamentBundle\Tests\Engine\Scoreboard;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Scoreboard\ScoreboardEntry;

class ScoreboardEntryTest extends \PHPUnit_Framework_TestCase
{
    public function testNotPlacedNotFinal()
    {
        $player = new Player();

        $scoreboardEntry = new ScoreboardEntry($player, 0, false);
        $this->assertEquals($player, $scoreboardEntry->getPlayer());
        $this->assertFalse($scoreboardEntry->isPlaced());
        $this->assertFalse($scoreboardEntry->isFinal());
    }

    public function testPlacedFinal()
    {
        $player = new Player();

        $scoreboardEntry = new ScoreboardEntry($player, 99, true);
        $this->assertEquals($player, $scoreboardEntry->getPlayer());
        $this->assertTrue($scoreboardEntry->isPlaced());
        $this->assertTrue($scoreboardEntry->isFinal());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNegativeScore()
    {
        new ScoreboardEntry(new Player(), -99, true);
    }
}
