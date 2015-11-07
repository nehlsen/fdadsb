<?php

namespace Fda\TournamentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Bolts\GameMode;
use Fda\TournamentBundle\Engine\Bolts\LegMode;
use Fda\TournamentBundle\Engine\Setup\RoundSetupAva;
use Fda\TournamentBundle\Engine\Setup\RoundSetupSeed;
use Fda\TournamentBundle\Engine\Setup\TournamentSetup;
use Fda\TournamentBundle\Entity\Tournament;

class LoadSimpleTournament extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var Board[] $boards */
        $boards = array(
            $this->getReference('board-0'),
        );
        /** @var Player[] $players */
        $players = array(
            $this->getReference('player-0'),
            $this->getReference('player-1'),
            $this->getReference('player-2'),
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

        $this->setReference('tournament-0', $tournament);

        $manager->persist($tournament);
        $manager->flush();
    }
}
