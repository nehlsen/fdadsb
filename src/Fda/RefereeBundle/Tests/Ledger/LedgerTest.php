<?php

namespace Fda\RefereeBundle\Tests\Ledger;

use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Entity\Player;
use Fda\RefereeBundle\Ledger\Ledger;
use Fda\TournamentBundle\Engine\Bolts\Arrow;
use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
use Fda\TournamentBundle\Engine\LeaderBoard\LeaderBoardInterface;
use Fda\TournamentBundle\Engine\TournamentEngineInterface;
use Fda\TournamentBundle\Entity\Tournament;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class LedgerTest extends WebTestCase
{
    /** @var \Doctrine\Common\DataFixtures\ReferenceRepository */
    protected $fixtures;

    public function setUp()
    {
        /** @var \Doctrine\Common\DataFixtures\Executor\AbstractExecutor $fixturesExecutor */
        $fixturesExecutor = $this->loadFixtures(array(
            'Fda\BoardBundle\DataFixtures\ORM\LoadSampleBoards',
            'Fda\PlayerBundle\DataFixtures\ORM\LoadSamplePlayers',
            'Fda\TournamentBundle\DataFixtures\ORM\LoadSimpleTournament',
        ));
        $this->fixtures = $fixturesExecutor->getReferenceRepository();
    }

    public function testRealSimpleTournament()
    {
        /** @var Tournament $tournament */
        $tournament = $this->fixtures->getReference('tournament-0');

        $engine = $this->getContainer()->get('fda.tournament.engine');
        $engine->setTournament($tournament);

        /** @var Player $referee */
        $referee = $this->fixtures->getReference('player-3');
        /** @var Board $board */
        $board = $this->fixtures->getReference('board-0');

        $ledger = $this->getContainer()->get('fda.ledger');
        $ledger->setOwner($referee);
        $ledger->setBoard($board);

        // --- init done
        // we should have 1 group
        $this->assertCount(1, $engine->getCurrentRoundGears()->getGameGearsGrouped());
        // 3 players ava should result in 3 games
        $this->assertCount(3, $engine->getCurrentRoundGears()->getGameGearsGrouped()[0]);

        // - select first game and play ...
        $this->playGame0($engine, $ledger);
        $this->checkLeaderBoard0($engine->getCurrentRoundGears()->getLeaderBoard());

        // - play the rest
        $this->playGame1($engine, $ledger);
        $this->playGame2($engine, $ledger);

        $this->checkLeaderBoardFinal($engine->getCurrentRoundGears()->getLeaderBoard());
    }

    protected function playGame0(TournamentEngineInterface $engine, Ledger $ledger)
    {
        /** @var GameGearsInterface $group0Game0Gears */
        $group0Game0Gears = $engine
            ->getCurrentRoundGears()
            ->getGameGearsGrouped()[0][0];
        $game = $group0Game0Gears->getGame();

        $this->assertEquals(3, $game->getPlayer1()->getId());
        $this->assertEquals(2, $game->getPlayer2()->getId());

        $ledger->setGameId($game->getId());

        // player 0
        $ledger->registerShot(Arrow::create(1, Arrow::MULTIPLIER_SINGLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));

        // player 1
        $ledger->registerShot(Arrow::create(7, Arrow::MULTIPLIER_SINGLE));
        $ledger->registerShot(Arrow::create(19, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(16, Arrow::MULTIPLIER_DOUBLE));

        // player 0
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));

        // player-0 won...
        $game = $ledger->getGame();
        $this->assertTrue($game->isClosed());
    }

    protected function checkLeaderBoard0(LeaderBoardInterface $leaderBoard)
    {
        $this->assertCount(1, $leaderBoard->getEntries());
        $this->assertCount(3, $leaderBoard->getGroupEntries(0));

        $entry0 = $leaderBoard->getGroupEntries(0)[0];
        $this->assertEquals(1, $entry0->getPoints());

//        /** @var Player $player0 */
//        $player0 = $this->fixtures->getReference('player-0');
        $this->assertEquals(3, $entry0->getPlayer()->getId());
    }

    protected function playGame1(TournamentEngineInterface $engine, Ledger $ledger)
    {
        /** @var GameGearsInterface $group0Game1Gears */
        $group0Game1Gears = $engine
            ->getCurrentRoundGears()
            ->getGameGearsGrouped()[0][1];
        $game = $group0Game1Gears->getGame();

        $ledger->setGameId($game->getId());

        // player 0
        $ledger->registerShot(Arrow::create(1, Arrow::MULTIPLIER_SINGLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));

        // player 2
        $ledger->registerShot(Arrow::create(7, Arrow::MULTIPLIER_SINGLE));
        $ledger->registerShot(Arrow::create(19, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(16, Arrow::MULTIPLIER_DOUBLE));

        // player 0
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));

        // player-0 won...
        $game = $ledger->getGame();
        $this->assertTrue($game->isClosed());
    }

    protected function playGame2(TournamentEngineInterface $engine, Ledger $ledger)
    {
        /** @var GameGearsInterface $group0Game1Gears */
        $group0Game1Gears = $engine
            ->getCurrentRoundGears()
            ->getGameGearsGrouped()[0][2];
        $game = $group0Game1Gears->getGame();

        $ledger->setGameId($game->getId());

        // player 1
        $ledger->registerShot(Arrow::create(1, Arrow::MULTIPLIER_SINGLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));

        // player 2
        $ledger->registerShot(Arrow::create(7, Arrow::MULTIPLIER_SINGLE));
        $ledger->registerShot(Arrow::create(19, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(16, Arrow::MULTIPLIER_DOUBLE));

        // player 1
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));
        $ledger->registerShot(Arrow::create(20, Arrow::MULTIPLIER_TRIPLE));

        // player-1 won...
        $game = $ledger->getGame();
        $this->assertTrue($game->isClosed());
    }

    protected function checkLeaderBoardFinal(LeaderBoardInterface $leaderBoard)
    {
        $this->assertCount(1, $leaderBoard->getEntries());
        $this->assertCount(3, $leaderBoard->getGroupEntries(0));

        $entry0 = $leaderBoard->getGroupEntries(0)[0];
        $this->assertEquals(2, $entry0->getPoints());

        $entry1 = $leaderBoard->getGroupEntries(0)[1];
        $this->assertEquals(1, $entry1->getPoints());

        $entry2 = $leaderBoard->getGroupEntries(0)[2];
        $this->assertEquals(0, $entry2->getPoints());

//        /** @var Player $player0 */
//        $player0 = $this->fixtures->getReference('player-0');
//        $this->assertEquals($player0, $entry0->getPlayer());
    }
}
