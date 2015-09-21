<?php

namespace Fda\RefereeBundle\Ledger;

use Fda\BoardBundle\Entity\Board;
use Fda\BoardBundle\Manager\BoardManager;
use Fda\PlayerBundle\Entity\Player;
use Fda\PlayerBundle\Manager\PlayerManager;
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

    /** @var Player */
    protected $owner;

    /** @var Board */
    protected $board;

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