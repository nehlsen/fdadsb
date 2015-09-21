<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Entity\Tournament;
use Fda\TournamentBundle\Entity\Turn;

// supports 301 and 501 with and without double-out
class SimpleLegGears extends AbstractLegGears
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var int */
    protected $requiredScore;

    /** @var bool */
    protected $requiredDoubleOut;

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
        if (false !== $lastTurn && !$lastTurn->isComplete() && !$lastTurn->isVoid()) {
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

        $this->checkScores($turn);

//        if ($arrowNumber == 3) {
//            // turn complete!
//        }

        $this->entityManager->persist($this->leg);
        $this->entityManager->persist($turn);

        return $turn->isVoid() ? 0 : 3 - $arrowNumber;
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

    /**
     * check if the turn is a bust and close the leg if won
     *
     * @param Turn $lastTurn
     */
    protected function checkScores(Turn $lastTurn)
    {
        $this->leg->updateScoresAndShots();

        $maxScore = $this->getRequiredScore();
        $doubleOutRequired = $this->isDoubleOutRequired();

        if ($lastTurn->getPlayer() == $this->gameGears->getGame()->getPlayer1()) {
            $score = $this->leg->getPlayer1score();
        } else {
            $score = $this->leg->getPlayer2score();
        }

        if ($score == $maxScore) {
            // check for double out
            if ($doubleOutRequired && !$lastTurn->getLastArrow()->isDouble()) {
                $lastTurn->setVoid();
            } else {
                // finished !!!
                $this->leg->setWinner($lastTurn->getPlayer());
                $this->gameGears->onLegComplete($this->leg);
            }
        } elseif ($score > $maxScore) {
            // last turn is a bust!
            $lastTurn->setVoid();
        }
    }

    /**
     * the score required to win the leg
     *
     * @return int
     */
    protected function getRequiredScore()
    {
        $this->initRequirements();
        return $this->requiredScore;
    }

    /**
     * whether it is required to end the leg with a double
     *
     * @return bool
     */
    protected function isDoubleOutRequired()
    {
        $this->initRequirements();
        return $this->requiredDoubleOut;
    }

    /**
     * init requirements to win the leg
     */
    protected function initRequirements()
    {
        if (null === $this->requiredScore) {
            $this->requiredDoubleOut = false;
            if ($this->getTournament()->getLegMode() == Tournament::LEG_301) {
                $this->requiredScore = 301;
            } elseif ($this->getTournament()->getLegMode() == Tournament::LEG_301_DOUBLE_OUT) {
                $this->requiredScore = 301;
                $this->requiredDoubleOut = true;
            } elseif ($this->getTournament()->getLegMode() == Tournament::LEG_501) {
                $this->requiredScore = 501;
            } elseif ($this->getTournament()->getLegMode() == Tournament::LEG_501_DOUBLE_OUT) {
                $this->requiredScore = 501;
                $this->requiredDoubleOut = true;
            }
        }
    }
}
