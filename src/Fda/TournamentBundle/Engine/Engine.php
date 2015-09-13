<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Entity\Tournament;

class Engine implements EngineInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * Engine constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $repository = $this->entityManager->getRepository('FdaTournamentBundle:Tournament');
        $tournament = $repository->findOneBy(array(
            'isClosed' => false,
        ));

        return $tournament;
    }

    /**
     * @inheritDoc
     */
    public function createTournament(Tournament $tournament)
    {
        if ($this->hasTournament()) {
            throw new \Exception('we already have an active tournament. there can only be one active tournament.');
        }

        $this->entityManager->persist($tournament);
        $this->entityManager->flush();
    }
}
