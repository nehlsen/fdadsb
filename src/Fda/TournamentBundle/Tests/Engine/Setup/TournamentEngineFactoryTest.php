<?php

namespace Fda\TournamentBundle\Tests\Engine\Setup;

use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Bolts\GameMode;
use Fda\TournamentBundle\Engine\Bolts\LegMode;
use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
use Fda\TournamentBundle\Engine\Setup\RoundSetupAva;
use Fda\TournamentBundle\Engine\Setup\RoundSetupSeed;
use Fda\TournamentBundle\Engine\Setup\TournamentSetup;
use Fda\TournamentBundle\Entity\Tournament;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class TournamentEngineFactoryTest extends WebTestCase
{
    protected $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'Fda\BoardBundle\DataFixtures\ORM\LoadSampleBoards',
            'Fda\PlayerBundle\DataFixtures\ORM\LoadSamplePlayers',
        ))->getReferenceRepository();
    }

    protected function getRealSimpleTournament()
    {
        /** @var Board[] $boards */
        $boards = array(
            $this->fixtures->getReference('board-0'),
        );
        /** @var Player[] $players */
        $players = array(
            $this->fixtures->getReference('player-0'),
            $this->fixtures->getReference('player-1'),
            $this->fixtures->getReference('player-2'),
        );

        $tournamentSetup = new TournamentSetup();

        $tournamentSetup->setSeed(RoundSetupSeed::create([[
            $players[0]->getId(),
            $players[1]->getId(),
            $players[2]->getId()
        ]]));
        $round1 = RoundSetupAva::createStraight();
        $round1->setGameMode(new GameMode(GameMode::FIRST_TO, 3));
        $round1->setLegMode(new LegMode(LegMode::SINGLE_OUT_301));
        $tournamentSetup->addRound($round1);

        $tournament = new Tournament();
        $tournament->setName('real simple');
        $tournament->setSetup($tournamentSetup);
        $tournament->setBoards($boards);
        $tournament->setPlayers($players);

        $entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $entityManager->persist($tournament);

        return $tournament;
    }

    public function testOne()
    {
        $tournament = $this->getRealSimpleTournament();

        $engineFactory = $this->getContainer()->get('fda.tournament.engine_factory');

        $gears = $engineFactory->initializeGears($tournament);
        $this->assertCount(2, $gears); // seed and one round

        // ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
        // --- seed tests
        $seedGears = $gears[0];
        $this->assertInstanceOf('Fda\TournamentBundle\Engine\Gears\RoundGearsSeed', $seedGears);
        $seedRound = $seedGears->getRound();
        $this->assertEquals($tournament, $seedRound->getTournament());
        $this->assertEquals(0, $seedRound->getNumber());

        // seed round shall always be closed
        $this->assertTrue($seedGears->isRoundClosed());

        // seed shall have one group
        $this->assertCount(1, $seedRound->getGroups(), 'expected to find exactly one group in seed-round');
        $seedGroup0 = $seedRound->getGroups()[0];

        // seeds one group shall have three players
        $this->assertCount(3, $seedGroup0->getPlayers());

        // seed shall never have games
        $this->assertEmpty($seedGroup0->getGames());

        // ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- ----- -----
        // --- 1st round tests
        $round1gears = $gears[1];
        $this->assertInstanceOf('Fda\TournamentBundle\Engine\Gears\RoundGearsAva', $round1gears);
        $round1 = $round1gears->getRound();
        $this->assertEquals($tournament, $round1->getTournament());
        $this->assertEquals(1, $round1->getNumber());

        // no games yet, first actual round shall be open
        $this->assertFalse($round1gears->isRoundClosed());

        // round1 shall have one group
        $this->assertCount(1, $round1->getGroups());
        $round1Group0 = $round1->getGroups()[0];

        // round1s one group shall have three players
        $this->assertCount(3, $round1Group0->getPlayers());

        // expect to find one group in round1
        $this->assertCount(1, $round1gears->getGameGearsGrouped());

        // expect to find a list with three games in group0
        $group0 = $round1gears->getGameGearsGrouped()[0];
        $this->assertCount(3, $group0);

        /** @var GameGearsInterface $group0game0 */
        $group0game0 = $group0[0];
        $this->assertInstanceOf('Fda\TournamentBundle\Entity\Game', $group0game0->getGame());
    }
}
