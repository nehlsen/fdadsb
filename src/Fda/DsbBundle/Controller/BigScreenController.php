<?php

namespace Fda\DsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BigScreenController extends Controller
{
    public function indexAction()
    {
        $tournament = $this->get('fda.tournament.engine')->getTournament();

        return $this->render('FdaDsbBundle:BigScreen:index.html.twig', array(
            'tournament' => $tournament,
        ));
    }

    public function fetchBoardInfoAction($boardId)
    {
        $tournament = $this->get('fda.tournament.engine')->getTournament();
        if (null === $tournament) {
            return new Response('NO TOURNAMENT');
        }

        $board = $this->get('fda.board.manager')->getBoard($boardId);
        if (null === $boardId) {
            return new Response('INVALID BOARD');
        }

        $game = null;
        foreach ($tournament->getRunningGames() as $runningGame) {
            if ($runningGame->getBoard()->getId() == $board->getId()) {
                $game = $runningGame;
                break;
            }
        }

        if (null === $game) {
            $turn = null;
        } else {
            $gameGears = $this->get('fda.tournament.engine')->getGears()->getGameGears($game->getId());
            $legGears = $gameGears->getLegGears();
            $turn = $legGears->currentTurn();
        }

        return $this->render('FdaDsbBundle:BigScreen:boardInfo.html.twig', array(
            'game' => $game,
            'turn' => $turn,
        ));
    }
}
