<?php

namespace Fda\TournamentBundle\Engine\Factory;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Engine\Gears\LegGearsInterface;
use Fda\TournamentBundle\Engine\Gears\LegGearsSimple;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Leg;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LegGearsFactory
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * create leg-gears for "current-leg"
     *
     * if the last leg of the provided games is not closed, it is used.
     * else a new leg is created, persisted and used for the leg-gears.
     *
     * it is the responsibility of the caller to make sure the game actually needs
     * additional legs.
     *
     * @param Game $game
     *
     * @return LegGearsInterface
     */
    public function create(Game $game)
    {
        $currentLeg = null;

        $lastLeg = $game->getLegs()->last();
        if ($lastLeg instanceof Leg && !$lastLeg->isClosed()) {
            $currentLeg = $lastLeg;
        }

        if (null === $currentLeg) {
            $currentLeg = new Leg($game);
            $this->entityManager->persist($currentLeg);
        }

        $roundSetup = $game->getGroup()->getRound()->getSetup();
        $legMode = $roundSetup->getLegMode();

        if (in_array($legMode->getMode(), LegGearsSimple::getSupportedModes())) {
            $gears = new LegGearsSimple($currentLeg);
        } else {
            throw new \InvalidArgumentException('can not create leg-gears for '.$legMode->getMode());
        }

        $gears->setLogger($this->logger);
        $this->eventDispatcher->addSubscriber($gears);

        $this->entityManager->flush();

        return $gears;
    }
}
