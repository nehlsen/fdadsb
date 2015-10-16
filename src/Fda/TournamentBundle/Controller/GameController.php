<?php

namespace Fda\TournamentBundle\Controller;

use Fda\TournamentBundle\Entity\Game;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    /**
     * @Secure(roles="ROLE_USER")
     */
    public function showAction($gameId)
    {
        $game = $this->getGame($gameId);

        $tournamentEngine = $this->get('fda.tournament.engine');
        $tournamentEngine->setTournament($game->getTournament());
        $gameGears = $tournamentEngine->getGears()->getGameGears($gameId);

        return $this->render('FdaTournamentBundle:Game:show.html.twig', array(
            'game'      => $game,
            'gameGears' => $gameGears,
            ));
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('FdaTournamentBundle:Game');
    }

    /**
     * @param int $id Game-ID
     * @return Game
     */
    protected function getGame($id)
    {
        $game = $this->getRepository()->find($id);

        if (!$game) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        return $game;
    }
}