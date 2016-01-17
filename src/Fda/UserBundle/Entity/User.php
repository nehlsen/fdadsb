<?php

namespace Fda\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fda\PlayerBundle\Entity\Player;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Fda\PlayerBundle\Entity\Player",
     *     inversedBy="user",
     *     cascade={"persist"}
     * )
     * @var Player
     */
    protected $player;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }
}
