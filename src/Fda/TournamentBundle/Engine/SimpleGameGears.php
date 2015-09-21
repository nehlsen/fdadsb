<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Leg;

// supports game mode 'first-to' and 'ahead'
class SimpleGameGears extends AbstractGameGears
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var Game */
    protected $game;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function setGame($game_or_id)
    {
        if ($game_or_id instanceof Game) {
            $this->game = $game_or_id;
        } else {
            $this->game = $this->entityManager->getRepository('FdaTournamentBundle:Game')->find($game_or_id);
        }
    }

    /**
     * @inheritDoc
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentLeg()
    {
        $legs = $this->game->getLegs();

        // TODO determine current leg
        if (count($legs) > 0) {
            return $legs[0];
        } else {
            $leg = new Leg($this->game);
            $this->game->addLeg($leg);
        }

        return $leg;
    }
}
