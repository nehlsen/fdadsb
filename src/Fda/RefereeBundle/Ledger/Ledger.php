<?php

namespace Fda\RefereeBundle\Ledger;

use Fda\BoardBundle\Entity\Board;
use Fda\BoardBundle\Manager\BoardManager;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Bolts\Arrow;
use Fda\TournamentBundle\Engine\EngineEvents;
use Fda\TournamentBundle\Engine\Events\ArrowEvent;
use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
use Fda\TournamentBundle\Engine\TournamentEngineInterface;
use Fda\TournamentBundle\Entity\Game;
use Fda\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Ledger
{
    const SESSION_KEY_BOARD = 'ledger-board';

    /** @var Session */
    protected $session;

    /** @var BoardManager */
    protected $boardManager;

    /** @var TournamentEngineInterface */
    protected $tournamentEngine;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var Player */
    protected $owner;

    /** @var Board */
    protected $board;

    /** @var int */
    protected $gameId;

    /** @var GameGearsInterface */
    protected $gameGears;

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
        $this->autoStart();
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage($tokenStorage)
    {
        $token = $tokenStorage->getToken();
        if (!$token) {
            return;
        }

        /** @var User $user */
        $user = $token->getUser();
        if (!($user instanceof User)) {
            return;
        }

        $this->owner = $user->getPlayer();
    }

    /**
     * @param BoardManager $boardManager
     */
    public function setBoardManager($boardManager)
    {
        $this->boardManager = $boardManager;
        $this->autoStart();
    }

    /**
     * @param TournamentEngineInterface $tournamentEngine
     */
    public function setTournamentEngine(TournamentEngineInterface $tournamentEngine)
    {
        $this->tournamentEngine = $tournamentEngine;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return Player
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @param int|Board $board
     */
    public function setBoard($board)
    {
        if (!$board instanceof Board) {
            $board = $this->boardManager->getBoard($board);
        }

        $this->board = $board;
        $this->session->set(self::SESSION_KEY_BOARD, $this->board->getId());
    }

    /**
     * @param int $gameId
     */
    public function setGameId($gameId)
    {
        $this->gameId = $gameId;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->getGameGears()->getGame();
    }

    /**
     * @return GameGearsInterface
     */
    public function getGameGears()
    {
        if (null === $this->gameId) {
            throw new \RuntimeException(
                'Game-ID has to be set prior to using the Ledger!'
            );
        }

        if (null === $this->gameGears) {
            $this->gameGears = $this->tournamentEngine->getGameGearsForGameId($this->gameId);

            if (null === $this->gameGears) {
                throw new \RuntimeException(sprintf(
                    'Game-Gears for Game-ID %d not found!',
                    $this->gameId
                ));
            }
        }

        return $this->gameGears;
    }

    /**
     * called on incoming arrow
     *
     * @param Arrow $arrow
     */
    public function registerShot(Arrow $arrow)
    {
        // trigger initialization of leg-gears so they can listen for events
        $this->getGameGears()->getCurrentLegGears();

        $event = new ArrowEvent();
        $event->setArrow($arrow);

        $this->eventDispatcher->dispatch(
            EngineEvents::ARROW_INCOMING,
            $event
        );
    }

    /**
     * load session data if session and boardManager are set, else nothing
     */
    protected function autoStart()
    {
        if (null === $this->session) {
            return;
        }
        if (null === $this->boardManager) {
            return;
        }

        $this->restoreFromSession();
    }

    protected function restoreFromSession()
    {
        if ($this->session->has(self::SESSION_KEY_BOARD)) {
            $this->board = $this->boardManager->getBoard(
                $this->session->get(self::SESSION_KEY_BOARD)
            );
        }
    }
}
