<?php

namespace Fda\PlayerBundle\Twig;

use Fda\PlayerBundle\Entity\Player;
use Fda\PlayerBundle\Manager\PlayerManager;

class PlayerExtension extends \Twig_Extension
{
    /** @var PlayerManager */
    protected $playerManager;

    /**
     * @param PlayerManager $playerManager
     */
    public function setPlayerManager(PlayerManager $playerManager)
    {
        $this->playerManager = $playerManager;
    }

    /**
     * {@InheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'player_link' => new \Twig_Function_Method($this, 'playerLink', array(
                'is_safe' => array('html'),
                'needs_environment' => true
            )),
            'player_label' => new \Twig_Function_Method($this, 'playerLabel', array(
                'is_safe' => array('html'),
                'needs_environment' => true
            )),
        );
    }

    /**
     * print player link with image
     *
     * @param \Twig_Environment $environment
     * @param int|Player        $player_or_id
     * @param array             $options
     *
     * @return string
     */
    public function playerLink(\Twig_Environment $environment, $player_or_id, array $options = array())
    {
        $player = $this->player($player_or_id);

        return $environment->render('FdaPlayerBundle:_Twig:player_link.html.twig', array(
            'player'  => $player,
            'options' => $options,
        ));
    }

    /**
     * print player name with image
     *
     * @param \Twig_Environment $environment
     * @param int|Player        $player_or_id
     * @param array             $options
     *
     * @return string
     */
    public function playerLabel(\Twig_Environment $environment, $player_or_id, array $options = array())
    {
        $player = $this->player($player_or_id);

        $options = array_merge(array(
            'size' => 'default', // text, table, default(large)
        ), $options);

        return $environment->render('FdaPlayerBundle:_Twig:player_label.html.twig', array(
            'player'  => $player,
            'options' => $options,
        ));
    }

    /**
     * @param int|Player $player_or_id
     * @return Player
     */
    protected function player($player_or_id)
    {
        if ($player_or_id instanceof Player) {
            return $player_or_id;
        }

        return $this->playerManager->getPlayer($player_or_id);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'player';
    }
}
