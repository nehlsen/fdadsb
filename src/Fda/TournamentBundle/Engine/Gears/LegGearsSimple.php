<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Bolts\Arrow;
use Fda\TournamentBundle\Engine\Bolts\CountDownFinishingMoveProvider;
use Fda\TournamentBundle\Engine\Bolts\LegMode;
use Fda\TournamentBundle\Entity\Turn;

class LegGearsSimple extends AbstractLegGears
{
    public static function getSupportedModes()
    {
        return array(
            LegMode::SINGLE_OUT_301,
            LegMode::DOUBLE_OUT_301,
            LegMode::SINGLE_OUT_501,
            LegMode::DOUBLE_OUT_501,
        );
    }

    /**
     * @inheritDoc
     */
    public function getCurrentTurn()
    {
        /** @var Collection|Turn[] $turns */
        $turns = $this->leg->getTurns();
        $turns = is_array($turns) ? new ArrayCollection($turns) : $turns;

        /** @var Turn|null $lastTurn */
        $lastTurn = null === $turns ? false : $turns->last();
        if (false !== $lastTurn && !$lastTurn->isComplete() && !$lastTurn->isVoid()) {
            return $lastTurn;
        }

        $game = $this->leg->getGame();
        $player = $game->getPlayer1();
        if (false !== $lastTurn) {
            if ($lastTurn->getPlayer()->getId() == $player->getId()) {
                $player = $game->getPlayer2();
            }
        }

        $turn = new Turn($this->leg, $player);

        return $turn;
    }

    /**
     * @inheritDoc
     */
    public function remainingShots()
    {
        $turn = $this->getCurrentTurn();

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
     * {@InheritDoc}
     */
    public function getRequiredScore()
    {
        return $this->leg->getLegMode()->getRequiredScore();
    }

    /**
     * {@InheritDoc}
     */
    public function getScoreOf(Player $player)
    {
        $game = $this->leg->getGame();

        if ($game->getPlayer1() == $player) {
            return $this->leg->getPlayer1score();
        } elseif ($game->getPlayer2() == $player) {
            return $this->leg->getPlayer2score();
        } else {
            throw new \InvalidArgumentException();
        }
    }

    /**
     * {@InheritDoc}
     */
    public function getRemainingScoreOf(Player $player)
    {
        return $this->getRequiredScore() - $this->getScoreOf($player);
    }

    /**
     * {@InheritDoc}
     */
    public function getFinishingMovesOf(Player $player)
    {
        $provider = new CountDownFinishingMoveProvider(
            $this->getRemainingScoreOf($player),
            $this->leg->getLegMode()->isDoubleOutRequired()
        );

        return $provider->getFinishingMoves();
    }

    /**
     * @inheritDoc
     */
    protected function handleArrow(Arrow $arrow)
    {
        // set arrow number
        // do stuff
        // return arrow

        $remainingShots = $this->remainingShots();
        $arrowNumber = 4 - $remainingShots;
        $arrow->setNumber($arrowNumber);

        $turn = $this->updateTurn($arrow);

        if ($turn->isVoid() || $turn->isComplete()) {
            // turn closed!
            $this->setTurnCompleted($turn);
        }
        if ($this->leg->isClosed()) {
            $this->setLegCompleted($this->leg);
        }

        return $arrow;
    }

    /**
     * add arrow to turn, check for bust, re-calc scores of leg, close leg if necessary
     *
     * @param Arrow $arrow the incoming arrow
     *
     * @return Turn
     */
    protected function updateTurn(Arrow $arrow)
    {
        $turn = $this->getCurrentTurn();
        $turn->setArrow($arrow);
        $this->leg->updateScoresAndShots();

        $score = $this->leg->getScoreOf($turn->getPlayer());
        $maxScore = $this->getRequiredScore();
        $doubleOutRequired = $this->leg->getLegMode()->isDoubleOutRequired();

        if ($score == $maxScore) {
            // check for double out
            if ($doubleOutRequired && !$turn->getLastArrow()->isDouble()) {
                $turn->setVoid();
            } else {
                // finished !!!
                $this->leg->setWinner($turn->getPlayer());
            }
        } elseif ($score > $maxScore) {
            // last turn is a bust!
            $turn->setVoid();
        }

        if ($turn->isVoid()) {
            // re-calc needed...
            $this->leg->updateScoresAndShots();
        }

        return $turn;
    }
}
