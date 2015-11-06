<?php

namespace Fda\TournamentBundle\Engine\Factory;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Entity\Tournament;

/**
 * contrary to its name, this factory does not create the engine but the complete set of round gears
 * for the provided tournament
 *
 * round gears will be initialized in a way that missing Round entities are created, persisted and flushed
 */
class TournamentEngineFactory
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var RoundGearsFactory */
    protected $roundGearsFactory;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param RoundGearsFactory $roundGearsFactory
     */
    public function setRoundGearsFactory(RoundGearsFactory $roundGearsFactory)
    {
        $this->roundGearsFactory = $roundGearsFactory;
    }

    /**
     * create round gears for each round in tournament
     *
     * @param Tournament $tournament
     *
     * @return RoundGearsInterface[]
     */
    public function initializeGears(Tournament $tournament)
    {
        $gears = array();

        $setup = $tournament->getSetup();
        $roundNumber = 0;

        $seedGears = $this->roundGearsFactory->create($tournament, $roundNumber, $setup->getSeed());

        $gears[] = $seedGears;
        $previous = $seedGears;

        foreach ($setup->getRounds() as $roundSetup) {
            $roundGears = $this->roundGearsFactory->create($tournament, ++$roundNumber, $roundSetup);
            $roundGears->setPreviousRoundGears($previous);

            $gears[] = $roundGears;
            $previous = $roundGears;
        }

//        $this->entityManager->flush();

        return $gears;
    }
}
