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
     */
    public function playAction($gameId)
    {
        $gameGears = $this->get('fda.tournament.engine')->getGears()->getGameGears($gameId);
        $legGears = $gameGears->getLegGears();
        $turn = $legGears->currentTurn();
//        $turn->getPlayer(); // who is playing now

//        $legGears->registerArrow(10, Turn::MULTIPLIER_TRIPLE); // triple ten
//        $legGears->registerArrow(0);                           // missed the board
//        $legGears->registerArrow(7);                           // (single) seven

        $this->getDoctrine()->getManager()->flush();

        return $this->render('FdaRefereeBundle:Game:play.html.twig', array(
            'game'  => $gameGears->getGame(),
//            'ggears' => $gameGears,
            'leg_gears' => $legGears,
            'turn' => $turn,
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
        $gameGears = $this->get('fda.tournament.engine')->getGears()->getGameGears($gameId);
        $legGears = $gameGears->getLegGears();

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

        $remaining = $legGears->registerShot($score, $multiplier);
        $this->getDoctrine()->getManager()->flush();

        if ($remaining == 0) {
            // TODO does this require special handling?
        }

        return $this->redirectToRoute('game_play', array('gameId' => $gameId));
    }
}
