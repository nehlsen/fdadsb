<?php

namespace Fda\BoardBundle\Twig;

use Fda\BoardBundle\Entity\Board;
use Fda\BoardBundle\Manager\BoardManager;

class BoardExtension extends \Twig_Extension
{
    /** @var BoardManager */
    protected $boardManager;

    /**
     * @param BoardManager $boardManager
     */
    public function setBoardManager(BoardManager $boardManager)
    {
        $this->boardManager = $boardManager;
    }

    /**
     * {@InheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'board_link' => new \Twig_Function_Method($this, 'boardLink', array(
                'is_safe' => array('html'),
                'needs_environment' => true
            )),
            'board_label' => new \Twig_Function_Method($this, 'boardLabel', array(
                'is_safe' => array('html'),
                'needs_environment' => true
            )),
        );
    }

    /**
     * print board link with image
     *
     * @param \Twig_Environment $environment
     * @param int|Board         $board_or_id
     * @param array             $options
     *
     * @return string
     */
    public function boardLink(\Twig_Environment $environment, $board_or_id, array $options = array())
    {
        $board = $this->board($board_or_id);

        return $environment->render('FdaBoardBundle:_Twig:board_link.html.twig', array(
            'board'   => $board,
            'options' => $options,
        ));
    }

    /**
     * print board name with image
     *
     * @param \Twig_Environment $environment
     * @param int|Board         $board_or_id
     * @param array             $options
     *
     * @return string
     */
    public function boardLabel(\Twig_Environment $environment, $board_or_id, array $options = array())
    {
        $board = $this->board($board_or_id);

        return $environment->render('FdaBoardBundle:_Twig:board_label.html.twig', array(
            'board'   => $board,
            'options' => $options,
        ));
    }

    /**
     * @param int|Board $board_or_id
     * @return Board
     */
    protected function board($board_or_id)
    {
        if ($board_or_id instanceof Board) {
            return $board_or_id;
        }

        return $this->boardManager->getBoard($board_or_id);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'board';
    }
}
