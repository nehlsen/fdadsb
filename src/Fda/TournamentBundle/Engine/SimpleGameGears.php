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
        $currentLeg = null;

        $lastLeg = $this->game->getLegs()->last();
        if ($lastLeg instanceof Leg && !$lastLeg->isClosed()) {
            $currentLeg = $lastLeg;
        }

        if (null === $currentLeg) {
            $currentLeg = new Leg($this->game);
        }

        return $currentLeg;
    }

    /**
     * @inheritDoc
     */
    public function onLegComplete(Leg $leg)
    {
        // check if this completes the game
        //  or maybe changes anything else?
        // TODO: Implement onLegComplete() method.
    }
}
