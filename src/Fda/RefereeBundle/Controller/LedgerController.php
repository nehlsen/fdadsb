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
        $ledger = $this->get('fda.ledger');

        // make sure board is set
        $board = $ledger->getBoard();
        if (null === $board) {
            return $this->forward('FdaRefereeBundle:Ledger:chooseBoard');
        }

        return $this->render('FdaRefereeBundle:Ledger:start.html.twig');
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
