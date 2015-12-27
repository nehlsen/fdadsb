<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Engine\Events\RoundEvent;
use Fda\TournamentBundle\Engine\Events\TournamentEvent;
use Fda\TournamentBundle\Engine\Factory\TournamentEngineFactory;
use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;
use Fda\TournamentBundle\Entity\Tournament;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class TournamentEngine implements TournamentEngineInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var TournamentEngineFactory */
    protected $engineFactory;

    /** @var LoggerInterface */
    protected $logger;

    /** @var Tournament */
    protected $tournament;

    /** @var RoundGearsInterface[] */
    protected $roundGears = array();

    /** @var int */
    protected $currentRoundNumber;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
        if ($this->isTournamentCompleted()) {
            throw new \RuntimeException('can not get current round gears from a completed tournament');
        }

        return $this->roundGears[$this->getCurrentRoundNumber()];
    }

    public function getLastRoundGears()
    {
        if (count($this->roundGears) < 1) {
            $this->initializeGears();
        }

        return end($this->roundGears);
    }

    /**
     * @inheritDoc
     */
    public function getCurrentRoundNumber()
    {
        if (null === $this->currentRoundNumber) {
            $this->initializeGears();
        }

        return $this->currentRoundNumber;
    }

    /**
     * @inheritDoc
     */
    public function isTournamentCompleted()
    {
        return -1 == $this->getCurrentRoundNumber();
    }

    /**
     * @inheritDoc
     */
    public function getGameGearsForGameId($gameId)
    {
        // make sure initialization is triggered
        $this->initializeGears();

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
     * @inheritDoc
     */
    public function getGameGearsForBoardId($boardId)
    {
        // make sure initialization is triggered
        $this->initializeGears();

        $groupedGameGears = $this->getCurrentRoundGears()->getGameGearsGrouped();
        foreach ($groupedGameGears as $gameGears) {
            /** @var GameGearsInterface[] $gameGears */
            foreach ($gameGears as $gears) {
                if (!$gears->getGame()->isStarted()) {
                    continue;
                }
                if ($gears->getGame()->isClosed()) {
                    continue;
                }
                if ($boardId == $gears->getGame()->getBoard()->getId()) {
                    return $gears;
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
//            throw new \RuntimeException('All rounds claim to be complete!');
//            $this->getTournament()->setClosed(true);
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            EngineEvents::ROUND_COMPLETED => 'onRoundCompleted',
        );
    }

    /**
     * @param RoundEvent               $roundCompletedEvent
     * @param string                   $name
     * @param EventDispatcherInterface $dispatcher
     */
    public function onRoundCompleted(RoundEvent $roundCompletedEvent, $name, EventDispatcherInterface $dispatcher)
    {
        $tournamentComplete = true;
        foreach ($this->roundGears as $roundNumber => $gears) {
            if ($gears->isRoundCompleted()) {
                $this->log(sprintf('onRoundCompleted, round %d complete', $roundNumber));
                continue;
            }
            if ($gears->getRound()->getId() == $roundCompletedEvent->getRound()->getId()) {
                $this->log(sprintf('onRoundCompleted, round %d CONSIDER complete', $roundNumber));
                continue;
            }

            $this->log(sprintf('onRoundCompleted, round %d NOT complete', $roundNumber));
            $tournamentComplete = false;
            break;
        }

        if ($tournamentComplete) {
            $tournamentCompletedEvent = new TournamentEvent();
            $tournamentCompletedEvent->setTournament($this->getTournament());
            $dispatcher->dispatch(
                EngineEvents::TOURNAMENT_COMPLETED,
                $tournamentCompletedEvent
            );
        }
    }
}
