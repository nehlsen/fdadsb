<?php

namespace Fda\RefereeBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LedgerController extends Controller
{
    /**
     * @Secure(roles="ROLE_REFEREE")
     */
    public function startAction()
    {
        // if set, display owner, else form to choose
        // if set, display board, else form to choose
        $ledger = $this->get('fda.ledger');

        $owner = $ledger->getOwner();
        if (null === $owner) {
            return $this->forward('FdaRefereeBundle:Ledger:chooseOwner');
        }

        $board = $ledger->getBoard();
        if (null === $board) {
            return $this->forward('FdaRefereeBundle:Ledger:chooseBoard');
        }

        return $this->render('FdaRefereeBundle:Ledger:start.html.twig', array(
            'owner' => $owner,
            'board' => $board,
        ));
    }

    /**
     * @Secure(roles="ROLE_REFEREE")
     */
    public function chooseOwnerAction()
    {
        $players = $this->get('fda.player.manager')->getPlayers();

        return $this->render('FdaRefereeBundle:Ledger:chooseOwner.html.twig', array(
            'players' => $players,
        ));
    }

    /**
     * @Secure(roles="ROLE_REFEREE")
     */
    public function setOwnerAction(Request $request)
    {
        $ownerId = $request->get('id');
        $this->get('fda.ledger')->setOwner($ownerId);
        return $this->forward('FdaRefereeBundle:Ledger:start');
    }

    /**
     * @Secure(roles="ROLE_REFEREE")
     */
    public function chooseBoardAction()
    {
        $boards = $this->get('fda.tournament.engine')->getTournament()->getBoards();

        return $this->render('FdaRefereeBundle:Ledger:chooseBoard.html.twig', array(
            'boards' => $boards,
        ));
    }

    /**
     * @Secure(roles="ROLE_REFEREE")
     */
    public function setBoardAction(Request $request)
    {
        $boardId = $request->get('id');
        $this->get('fda.ledger')->setBoard($boardId);
        return $this->forward('FdaRefereeBundle:Ledger:start');
    }
}
