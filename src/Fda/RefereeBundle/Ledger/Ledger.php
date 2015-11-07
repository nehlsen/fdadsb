<?php

namespace Fda\RefereeBundle\Ledger;

use Fda\BoardBundle\Entity\Board;
use Fda\BoardBundle\Manager\BoardManager;
use Fda\PlayerBundle\Entity\Player;
use Fda\PlayerBundle\Manager\PlayerManager;
use Fda\TournamentBundle\Engine\Bolts\Arrow;
use Fda\TournamentBundle\Engine\EngineEvents;
use Fda\TournamentBundle\Engine\Events\ArrowEvent;
use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
use Fda\TournamentBundle\Engine\TournamentEngineInterface;
use Fda\TournamentBundle\Entity\Game;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class Ledger
{
    const SESSION_KEY_OWNER = 'ledger-owner';
    const SESSION_KEY_BOARD = 'ledger-board';

    /** @var Session */
    protected $session;

    /** @var PlayerManager */
    protected $playerManager;

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
     * @param PlayerManager $playerManager
     */
    public function setPlayerManager($playerManager)
    {
        $this->playerManager = $playerManager;
        $this->autoStart();
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
     * @param Player $owner
     */
    public function setOwner($owner)
    {
        if (!$owner instanceof Player) {
            $owner = $this->playerManager->getPlayer($owner);
        }

        $this->owner = $owner;
        $this->session->set(self::SESSION_KEY_OWNER, $this->owner->getId());
    }

    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @param Board $board
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
        if (null === $this->gameGears) {
            $this->gameGears = $this->tournamentEngine->getGameGearsForGameId($this->gameId);
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
     * load session data if session, playerManager and boardManager are set, else nothing
     */
    protected function autoStart()
    {
        if (null === $this->session) {
            return;
        }
        if (null === $this->playerManager) {
            return;
        }
        if (null === $this->boardManager) {
            return;
        }

        $this->restoreFromSession();
    }

    protected function restoreFromSession()
    {
        if ($this->session->has(self::SESSION_KEY_OWNER)) {
            $this->owner = $this->playerManager->getPlayer(
                $this->session->get(self::SESSION_KEY_OWNER)
            );
        }
        if ($this->session->has(self::SESSION_KEY_BOARD)) {
            $this->board = $this->boardManager->getBoard(
                $this->session->get(self::SESSION_KEY_BOARD)
            );
        }
    }
}
