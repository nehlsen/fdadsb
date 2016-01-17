<?php

namespace Fda\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fda\PlayerBundle\Entity\Player;

/**
 * @ORM\Entity
 */
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Fda\PlayerBundle\Entity\Player", inversedBy="invitation")
     * @var Player
     */
    protected $player;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $token;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * Invitation constructor.
     * @param Player $player
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->created = new \DateTime();
    }

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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        // TODO invitation might loose validity after e.g. two weeks
        return true;
    }
}
