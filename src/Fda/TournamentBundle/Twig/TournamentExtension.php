<?php

namespace Fda\TournamentBundle\Twig;

use Fda\TournamentBundle\Engine\EngineInterface;
use Fda\TournamentBundle\Entity\Tournament;
use Fda\TournamentBundle\Manager\TournamentManager;

class TournamentExtension extends \Twig_Extension
{
    /** @var EngineInterface */
    protected $tournamentEngine;

    /** @var TournamentManager */
    protected $tournamentManager;

    /**
     * @param EngineInterface $tournamentEngine
     */
    public function setTournamentEngine($tournamentEngine)
    {
        $this->tournamentEngine = $tournamentEngine;
    }

    /**
     * @param TournamentManager $tournamentManager
     */
    public function setTournamentManager(TournamentManager $tournamentManager)
    {
        $this->tournamentManager = $tournamentManager;
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
            'tournament_link' => new \Twig_Function_Method($this, 'tournamentLink', array(
                'is_safe' => array('html'),
                'needs_environment' => true
            )),
            'tournament_label' => new \Twig_Function_Method($this, 'tournamentLabel', array(
                'is_safe' => array('html'),
                'needs_environment' => true
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
     * print tournament link with icon
     *
     * @param \Twig_Environment $environment
     * @param int|Tournament    $tournament_or_id
     * @param array             $options
     *
     * @return string
     */
    public function tournamentLink(\Twig_Environment $environment, $tournament_or_id, array $options = array())
    {
        $tournament = $this->tournament($tournament_or_id);

        return $environment->render('FdaTournamentBundle:_Twig:tournament_link.html.twig', array(
            'tournament' => $tournament,
            'options'    => $options,
        ));
    }

    /**
     * print tournament name with icon
     *
     * @param \Twig_Environment $environment
     * @param int|Tournament    $tournament_or_id
     * @param array             $options
     *
     * @return string
     */
    public function tournamentLabel(\Twig_Environment $environment, $tournament_or_id, array $options = array())
    {
        $tournament = $this->tournament($tournament_or_id);

        return $environment->render('FdaTournamentBundle:_Twig:tournament_label.html.twig', array(
            'tournament' => $tournament,
            'options'    => $options,
        ));
    }

    /**
     * @param int|Tournament $tournament_or_id
     * @return Tournament
     */
    protected function tournament($tournament_or_id)
    {
        if ($tournament_or_id instanceof Tournament) {
            return $tournament_or_id;
        }

        return $this->tournamentManager->getTournament($tournament_or_id);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'tournament';
    }
}
