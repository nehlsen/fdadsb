<?php

namespace Fda\RefereeBundle\Tests\Ledger;

use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Bolts\Arrow;
use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
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
        // - select first game and play ...
        /** @var GameGearsInterface $group0Game0Gears */
        $group0Game0Gears = $engine
            ->getCurrentRoundGears()
            ->getGameGearsGrouped()[0][0];
        $game = $group0Game0Gears->getGame();

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
    }
}
