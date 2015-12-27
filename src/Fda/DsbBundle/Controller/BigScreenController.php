<?php

namespace Fda\DsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BigScreenController extends Controller
{
    public function indexAction()
    {
        return $this->render('FdaDsbBundle:BigScreen:index.html.twig');
    }

    public function fetchBoardInfoAction($boardId)
    {
        $tournamentEngine = $this->get('fda.tournament.engine');
        if (!$tournamentEngine->hasTournament()) {
            return new Response('NO TOURNAMENT');
        }

        $gameGears = $tournamentEngine->getGameGearsForBoardId($boardId);

        return $this->render('FdaDsbBundle:BigScreen:boardInfo.html.twig', array(
            'gears' => $gameGears,
        ));
    }
}
