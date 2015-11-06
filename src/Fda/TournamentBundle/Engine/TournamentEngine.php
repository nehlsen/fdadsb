<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Engine\Factory\TournamentEngineFactory;
use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Entity\Tournament;

class TournamentEngine implements TournamentEngineInterface
{
    /** @var EntityManager */
    protected $entityManager;

//    /** @var RoundGearsFactory */
//    protected $roundGearsFactory;

    /** @var TournamentEngineFactory */
    protected $engineFactory;

    /** @var Tournament */
    protected $tournament;

    /** @var RoundGearsInterface[] */
    protected $roundGears = array();

    /** @var int */
    protected $currentRoundNumber = 0;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

//    /**
//     * @param RoundGearsFactory $roundGearsFactory
//     */
//    public function setRoundGearsFactory(RoundGearsFactory $roundGearsFactory)
//    {
//        $this->roundGearsFactory = $roundGearsFactory;
//    }

    /**
     * @param TournamentEngineFactory $engineFactory
     */
    public function setEngineFactory(TournamentEngineFactory $engineFactory)
    {
        $this->engineFactory = $engineFactory;
    }

    /**
     * @inheritDoc
     */
    public function ensureReady()
    {
        $this->initializeGears();
    }

    /**
     * @inheritDoc
     */
    public function hasTournament()
    {
        return null !== $this->getTournament();
    }

    /**
     * @inheritDoc
     */
    public function getTournament()
    {
        if (null !== $this->tournament) {
            return $this->tournament;
        }

        $repository = $this->entityManager->getRepository('FdaTournamentBundle:Tournament');
        $this->tournament = $repository->findOneBy(array(
            'isClosed' => false,
        ));

        return $this->tournament;
    }

    /**
     * @inheritDoc
     */
    public function setTournament(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->roundGears = array();
    }

    /**
     * @inheritDoc
     */
    public function getCurrentRoundGears()
    {
        if (count($this->roundGears) < 1) {
            $this->initializeGears();
        }

        return $this->roundGears[$this->getCurrentRoundNumber()];
    }

    /**
     * @inheritDoc
     */
    public function getCurrentRoundNumber()
    {
        return $this->currentRoundNumber;
    }

    /**
     * initialize round-gears using the gears factory
     *
     * does nothing if gears is not empty
     *
     * @throws \Exception
     */
    protected function initializeGears()
    {
        if (count($this->roundGears) > 0) {
            return;
        }
        if (!$this->hasTournament()) {
            throw new \Exception();
        }

        $this->roundGears = $this->engineFactory->initializeGears($this->getTournament());

//        $tournament = $this->getTournament();
//        $setup = $tournament->getSetup();
//        $roundNumber = 0;
//
//        $seedGears = $this->roundGearsFactory->create($setup->getSeed());
//        $seedRound = $tournament->getRoundByNumber($roundNumber);
//        $seedGears->setRound($seedRound);
//        $this->entityManager->persist($seedRound);
//
//        $this->roundGears[] = $seedGears;
//        $previous = $seedGears;
//
//        foreach ($setup->getRounds() as $roundSetup) {
//            $roundGears = $this->roundGearsFactory->create($roundSetup);
//            $roundGears->setPreviousRoundGears($previous);
//            $round = $tournament->getRoundByNumber(++$roundNumber);
//            $roundGears->setRound($round);
//            $this->entityManager->persist($round);
//
//            $this->roundGears[] = $roundGears;
//            $previous = $roundGears;
//        }
//
//        $this->entityManager->flush();

        // TODO find first not closed round (except seed)
        $this->currentRoundNumber = 1;
    }
}
