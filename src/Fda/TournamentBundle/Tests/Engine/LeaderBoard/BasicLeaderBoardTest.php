<?php

namespace Fda\TournamentBundle\Tests\Engine\LeaderBoard;

use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\LeaderBoard\BasicLeaderBoard;

class BasicLeaderBoardTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $board = new BasicLeaderBoard();
        $this->assertEmpty($board->getGroupNumbers());
    }

    public function testOneGroupOnePlayer()
    {
        $board = $this->createEvenFilledLeaderBoard(1, 1);
        $this->assertCount(1, $board->getGroupNumbers());

        $this->assertCount(1, $board->getGroupEntries(0));
        $this->assertCount(1, $board->getGroupEntries(0, null));
        $this->assertCount(1, $board->getGroupEntries(0, -1));
        $this->assertCount(1, $board->getGroupEntries(0, 1));
        $this->assertCount(0, $board->getGroupEntries(0, 0));

        $this->assertCount(1, $board->getEntries());
        $this->assertCount(1, $board->getEntries()[0]);
        $this->assertCount(1, $board->getEntries(null));
        $this->assertCount(1, $board->getEntries(null)[0]);
        $this->assertCount(1, $board->getEntries(-1));
        $this->assertCount(1, $board->getEntries(-1)[0]);
        $this->assertCount(1, $board->getEntries(1));
        $this->assertCount(1, $board->getEntries(1)[0]);
        $this->assertCount(1, $board->getEntries(0));
        $this->assertCount(0, $board->getEntries(0)[0]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFetchNegativeAmount()
    {
        $board = $this->createEvenFilledLeaderBoard(1, 1);
        $board->getGroupEntries(0, -2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidGroupNumber()
    {
        $board = $this->createEvenFilledLeaderBoard(1, 1);
        $board->getGroupEntries(1);
    }

    public function testBig()
    {
        $board = $this->createEvenFilledLeaderBoard(4, 5);
        $this->assertCount(4, $board->getGroupNumbers());

        $this->assertCount(5, $board->getGroupEntries(0));
        $this->assertCount(5, $board->getGroupEntries(0, null));
        $this->assertCount(5, $board->getGroupEntries(0, -1));
        $this->assertCount(1, $board->getGroupEntries(0, 1));
        $this->assertCount(3, $board->getGroupEntries(0, 3));
        $this->assertCount(0, $board->getGroupEntries(0, 0));

        $this->assertCount(5, $board->getGroupEntries(3));
        $this->assertCount(1, $board->getGroupEntries(3, 1));
        $this->assertCount(3, $board->getGroupEntries(3, 3));

        // --- do some updates

        $this->assertEquals(5, $board->getGroupEntries(0)[0]->getPoints());
        $this->assertEquals(4, $board->getGroupEntries(0)[1]->getPoints());
        $this->assertEquals(3, $board->getGroupEntries(0)[2]->getPoints());
        $this->assertEquals(2, $board->getGroupEntries(0)[3]->getPoints());
        $this->assertEquals(1, $board->getGroupEntries(0)[4]->getPoints());

        $multiplier = 1;
        foreach ($board->getGroupEntries(0) as $groupEntry) {
            $board->update(0, $groupEntry->getPlayer(), ++$multiplier*$groupEntry->getPoints());
        }

        $this->assertEquals(16, $board->getGroupEntries(0)[0]->getPoints());
        $this->assertEquals(15, $board->getGroupEntries(0)[1]->getPoints());
        $this->assertEquals(15, $board->getGroupEntries(0)[2]->getPoints());
        $this->assertEquals(12, $board->getGroupEntries(0)[3]->getPoints());
        $this->assertEquals( 7, $board->getGroupEntries(0)[4]->getPoints());

        $board->update(0, $board->getGroupEntries(0)[4]->getPlayer(), 14);
        $this->assertEquals(21, $board->getGroupEntries(0, 3)[0]->getPoints());
        $this->assertEquals(16, $board->getGroupEntries(0, 3)[1]->getPoints());
        $this->assertEquals(15, $board->getGroupEntries(0, 3)[2]->getPoints());
    }

    protected function createEvenFilledLeaderBoard($groups, $playersPerGroup)
    {
        $board = new BasicLeaderBoard();

        $playerReflection = new \ReflectionClass('Fda\PlayerBundle\Entity\Player');
        $idAccessor = $playerReflection->getProperty('id');
        $idAccessor->setAccessible(true);

        for ($group = 0; $group < $groups; ++$group) {
            for ($player = 0; $player < $playersPerGroup; ++$player) {
                $newPlayer = new Player();
                $idAccessor->setValue($newPlayer, ($group+1).($player+1));
                $board->update($group, $newPlayer, ($group+1)*($player+1));
            }
        }

        return $board;
    }
}
