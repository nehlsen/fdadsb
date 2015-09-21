<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Entity\Tournament;
use Symfony\Component\DependencyInjection\ContainerAware;

class Engine extends ContainerAware implements EngineInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var TournamentGearsInterface */
    protected $tournamentGears;

    /** @var GameGearsInterface */
    protected $gameGears;

    /** @var LegGearsInterface */
    protected $legGears;

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

    /**
     * @inheritDoc
     */
    public function getGears()
    {
        if (null === $this->tournamentGears) {
            if ($this->getTournament()->getTournamentMode() == Tournament::TOURNAMENT_ALL_VS_ALL) {
                $this->tournamentGears = $this->container->get('fda.tournament.gears.all_vs_all');
            }
        }

        return $this->tournamentGears;
    }

    /**
     * @inheritDoc
     */
    public function getGameGears()
    {
        if (null === $this->gameGears) {
            if ($this->getTournament()->getGameMode() == Tournament::GAME_AHEAD ||
                $this->getTournament()->getGameMode() == Tournament::GAME_FIRST_TO) {
                $this->gameGears = $this->container->get('fda.game.gears.simple');
            }
        }

        return $this->gameGears;
    }

    /**
     * @inheritDoc
     */
    public function getLegGears()
    {
        if (null === $this->legGears) {
            if ($this->getTournament()->getLegMode() == Tournament::LEG_301 ||
                $this->getTournament()->getLegMode() == Tournament::LEG_301_DOUBLE_OUT ||
                $this->getTournament()->getLegMode() == Tournament::LEG_501 ||
                $this->getTournament()->getLegMode() == Tournament::LEG_501_DOUBLE_OUT) {
                $this->legGears = $this->container->get('fda.leg.gears.simple');
            }
        }

        return $this->legGears;
    }
}
