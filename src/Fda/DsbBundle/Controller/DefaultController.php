<?php

namespace Fda\DsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $authorizationChecker = $this->get('security.authorization_checker');
        if ($authorizationChecker->isGranted('ROLE_REFEREE')) {
            return $this->forward('FdaRefereeBundle:Ledger:start');
        }

        $tournament = $this->get('fda.tournament.engine')->getTournament();

        return $this->render('FdaDsbBundle:Default:index.html.twig', array(
            'tournament' => $tournament,
        ));
    }
}
