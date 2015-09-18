<?php

namespace Fda\TournamentBundle\Twig;

use Fda\TournamentBundle\Engine\EngineInterface;
use Fda\TournamentBundle\Entity\Tournament;

class TournamentExtension extends \Twig_Extension
{
    /** @var EngineInterface */
    protected $tournamentEngine;

    /**
     * @param EngineInterface $tournamentEngine
     */
    public function setTournamentEngine($tournamentEngine)
    {
        $this->tournamentEngine = $tournamentEngine;
    }

    /**
     * {@InheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'active_tournament' => new \Twig_Function_Method($this, 'getActiveTournament', array(
//                'is_safe' => array('html'),
//                'needs_environment' => true
            )),
            'tournament_engine' => new \Twig_Function_Method($this, 'getTournamentEngine', array(
//                'is_safe' => array('html'),
//                'needs_environment' => true
            )),
        );
    }

    /**
     * @return Tournament|null
     */
    public function getActiveTournament()
    {
        return $this->getTournamentEngine()->getTournament();
    }

    /**
     * @return EngineInterface
     */
    public function getTournamentEngine()
    {
        return $this->tournamentEngine;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'tournament';
    }
}
