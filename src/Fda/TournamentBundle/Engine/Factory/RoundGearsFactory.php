<?php

namespace Fda\TournamentBundle\Engine\Factory;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Engine\Gears\RoundGearsAva;
use Fda\TournamentBundle\Engine\Gears\RoundGearsBvw;
use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Engine\Gears\RoundGearsNull;
use Fda\TournamentBundle\Engine\Gears\RoundGearsSeed;
use Fda\TournamentBundle\Engine\Setup\RoundSetupAva;
use Fda\TournamentBundle\Engine\Setup\RoundSetupBvw;
use Fda\TournamentBundle\Engine\Setup\RoundSetupInterface;
use Fda\TournamentBundle\Engine\Setup\RoundSetupNull;
use Fda\TournamentBundle\Engine\Setup\RoundSetupSeed;
use Fda\TournamentBundle\Entity\Round;
use Fda\TournamentBundle\Entity\Tournament;

class RoundGearsFactory
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var GameGearsFactory */
    protected $gameGearsFactory;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param GameGearsFactory $gameGearsFactory
     */
    public function setGameGearsFactory(GameGearsFactory $gameGearsFactory)
    {
        $this->gameGearsFactory = $gameGearsFactory;
    }

    /**
     * @param Tournament          $tournament
     * @param int                 $roundNumber
     * @param RoundSetupInterface $roundSetup
     *
     * @return RoundGearsInterface
     */
    public function create(Tournament $tournament, $roundNumber, RoundSetupInterface $roundSetup)
    {
        $round = $tournament->getRoundByNumber($roundNumber);
        if (null === $round) {
            $round = new Round($tournament, $roundNumber);
            $this->entityManager->persist($round);
        }

        if ($roundSetup instanceof RoundSetupAva) {
            $gears = new RoundGearsAva($round, $roundSetup);
        } elseif ($roundSetup instanceof RoundSetupBvw) {
            $gears = new RoundGearsBvw($round, $roundSetup);
        } elseif ($roundSetup instanceof RoundSetupNull) {
            $gears = new RoundGearsNull($round, $roundSetup);
        } elseif ($roundSetup instanceof RoundSetupSeed) {
            $gears = new RoundGearsSeed($round, $roundSetup);
        } else {
            throw new \InvalidArgumentException('can not create round-gears for '.get_class($roundSetup));
        }

        $gears->setGameGearsFactory($this->gameGearsFactory);

        $this->entityManager->flush();

        return $gears;
    }
}
