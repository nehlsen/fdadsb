<?php

namespace Fda\DsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $tournament = $this->get('fda.tournament.engine')->getTournament();

        return $this->render('FdaDsbBundle:Default:index.html.twig', array(
            'tournament' => $tournament,
        ));
    }
}
