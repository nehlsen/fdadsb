<?php

namespace Fda\TournamentBundle\Engine;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Leg;
use Fda\TournamentBundle\Entity\Tournament;

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

        $this->game->updateWonLegs();
        $winner = $this->getWinner();
        if (null !== $winner) {
            $this->game->setWinner($winner);
            $this->tournamentGears->onGameComplete($this->game);
        }

        $this->entityManager->persist($this->game);
    }

    protected function getWinner()
    {
        if ($this->getTournament()->getGameMode() == Tournament::GAME_AHEAD) {
            $player1count = $this->game->getLegsWonPlayer1() - $this->game->getLegsWonPlayer2();
            $player2count = $this->game->getLegsWonPlayer2() - $this->game->getLegsWonPlayer1();
        } elseif ($this->getTournament()->getGameMode() == Tournament::GAME_FIRST_TO) {
            $player1count = $this->game->getLegsWonPlayer1();
            $player2count = $this->game->getLegsWonPlayer2();
        } else {
            throw new \Exception();
        }

        if ($player1count == $this->getTournament()->getGameCount()) {
            return $this->game->getPlayer1();
        } elseif ($player2count == $this->getTournament()->getGameCount()) {
            return $this->game->getPlayer2();
        }

        return null;
    }
}
