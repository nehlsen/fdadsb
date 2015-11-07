<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Doctrine\Common\Collections\Collection;
use Fda\PlayerBundle\Entity\Player;
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
}
