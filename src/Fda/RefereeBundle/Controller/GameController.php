<?php

namespace Fda\RefereeBundle\Controller;

use Fda\TournamentBundle\Entity\Turn;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    /**
     * @Secure(roles="ROLE_REFEREE")
     * @param int $gameId
     *
     * @return Response
     *
     * @throws \Exception if game is already closed
     */
    public function playAction($gameId)
    {
        $ledger = $this->get('fda.ledger');
        $ledger->setGameId($gameId);

        $gameGears = $ledger->getGameGears();
        $game = $gameGears->getGame();
//        if ($game->isClosed()) {
//            throw new \Exception('game closed!');
//        }

        if ($game->getLegs()->isEmpty()) {
            // fresh game! set referee and board!
            $game->setBoard($ledger->getBoard());
            $game->setReferee($ledger->getOwner());
        }
        // TODO prevent moving game to different board
        // TODO prevent stealing game from different referee

        $legGears = $gameGears->getCurrentLegGears();
        $turn = $legGears->getCurrentTurn();

        $this->getDoctrine()->getManager()->flush();

        return $this->render('FdaRefereeBundle:Game:play.html.twig', array(
            'game'      => $ledger->getGame(),
            'leg_gears' => $legGears,
            'turn'      => $turn,
        ));
    }

    /**
     * @Secure(roles="ROLE_REFEREE")
     * @param Request $request
     * @param int     $gameId
     *
     * @return Response
     */
    public function registerShotAction(Request $request, $gameId)
    {
        $score = $request->get('score', 0);
        $multiplier = $request->get('multiplier', 1);
        switch ($multiplier) {
            default:
            case 1:
                $multiplier = Turn::MULTIPLIER_SINGLE;
                break;
            case 2:
                $multiplier = Turn::MULTIPLIER_DOUBLE;
                break;
            case 3:
                $multiplier = Turn::MULTIPLIER_TRIPLE;
                break;
        }

        $tournamentEngine = $this->get('fda.tournament.engine');
        $continueGame = $tournamentEngine->registerShot($gameId, $score, $multiplier);
        if (!$continueGame) {
            // make sure the correct referee is set in the end
            $game = $tournamentEngine->getGears()->getGameGears($gameId)->getGame();
            $this->setGameBoardAndReferee($game);
        }

        $this->getDoctrine()->getManager()->flush();

        if ($continueGame) {
            return $this->redirectToRoute('game_play', array('gameId' => $gameId));
        } else {
            return $this->redirectToRoute('ledger_start');
        }
    }
}
