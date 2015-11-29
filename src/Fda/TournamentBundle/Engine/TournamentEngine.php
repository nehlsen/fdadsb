<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Engine\Factory\TournamentEngineFactory;
use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Entity\Tournament;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class TournamentEngine implements TournamentEngineInterface
{
    /** @var EntityManager */
    protected $entityManager;

//    /** @var RoundGearsFactory */
//    protected $roundGearsFactory;

    /** @var TournamentEngineFactory */
    protected $engineFactory;

    /** @var LoggerInterface */
    protected $logger;

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
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * log message to debug log (if logger has been set)
     * @param string $message
     */
    protected function log($message)
    {
        if ($this->logger) {
            $this->logger->debug(sprintf(
                'TournamentEngine:%s',
                $message
            ));
        }
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
     * @inheritDoc
     */
    public function getGameGearsForGameId($gameId)
    {
        // make sure initialization is triggered
        $this->getCurrentRoundGears();

        foreach ($this->roundGears as $roundGears) {
            $groupedGameGears = $roundGears->getGameGearsGrouped();
            foreach ($groupedGameGears as $gameGears) {
                /** @var GameGearsInterface[] $gameGears */
                foreach ($gameGears as $gears) {
                    if ($gameId == $gears->getGame()->getId()) {
                        return $gears;
                    }
                }
            }
        }

        return null;
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

        $this->currentRoundNumber = -1;
        foreach ($this->roundGears as $roundNumber => $gears) {
            if (!$gears->isRoundCompleted()) {
                $this->log(sprintf('initializeGears, round %d not complete', $roundNumber));
                $this->currentRoundNumber = $roundNumber;
                break;
            }

            $this->log(sprintf('initializeGears, round %d complete', $roundNumber));
        }

        if (-1 == $this->currentRoundNumber) {
            throw new \RuntimeException('All rounds claim to be complete!');
        }
    }
}
