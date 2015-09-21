<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Entity\Turn;

// supports 301 and 501 with and without double-out
class SimpleLegGears extends AbstractLegGears
{
    /** @var EntityManager */
    protected $entityManager;

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
    public function currentTurn()
    {
        /** @var Collection|Turn[] $turns */
        $turns = $this->leg->getTurns();

        /** @var Turn|null $lastTurn */
        $lastTurn = null === $turns ? false : $turns->last();
        if (false !== $lastTurn && !$lastTurn->isComplete()) {
            return $lastTurn;
        }

        $player = $this->gameGears->getGame()->getPlayer1();
        if (false !== $lastTurn) {
            if ($lastTurn->getPlayer()->getId() == $player->getId()) {
                $player = $this->gameGears->getGame()->getPlayer2();
            }
        }

        $turn = new Turn($this->leg, $player);

        return $turn;
    }

    /**
     * @inheritDoc
     */
    public function registerShot($score, $multiplier = Turn::MULTIPLIER_SINGLE)
    {
        $remainingShots = $this->remainingShots();
        $arrowNumber = 4 - $remainingShots;

        if ($arrowNumber > 3) {
            throw new \RuntimeException('current turn already registered 3 arrows');
        } elseif ($arrowNumber < 1) {
            throw new \RuntimeException('duck and cover! arrows flying in reverse!');
        }

        $turn = $this->currentTurn();
        $turn->setArrow($arrowNumber, $score, $multiplier);

        $this->entityManager->persist($turn);

        if ($arrowNumber == 3) {
            // TODO call parent:turn-finished
//            $this->gameGears->notifyTurnFinished($this);
        }

        return 3 - $arrowNumber;
    }

    /**
     * @inheritDoc
     */
    public function remainingShots()
    {
        $turn = $this->currentTurn();

        if ($turn->hasArrow(3)) {
            return 0;
        }
        if ($turn->hasArrow(2)) {
            return 1;
        }
        if ($turn->hasArrow(1)) {
            return 2;
        }

        return 3;
    }
}
