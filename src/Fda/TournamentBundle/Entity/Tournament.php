<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Setup\TournamentSetupInterface;

/**
 * @ORM\Entity
 */
class Tournament
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="object")
     * @var TournamentSetupInterface
     */
    protected $setup;

    /**
     * @ORM\ManyToMany(targetEntity="Fda\BoardBundle\Entity\Board", inversedBy="tournaments")
     * @var Board[]
     */
    protected $boards;

    /**
     * @ORM\ManyToMany(targetEntity="Fda\PlayerBundle\Entity\Player", inversedBy="tournaments")
     * @var Player[]
     */
    protected $players;

    /**
     * @ORM\OneToMany(
     *  targetEntity="Fda\TournamentBundle\Entity\Round",
     *  mappedBy="tournament",
     *  cascade={"persist"}
     * )
     * @var Round[]
     */
    protected $rounds;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected $isClosed = false;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    public function __construct()
    {
        $this->rounds = new ArrayCollection();
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return TournamentSetupInterface
     */
    public function getSetup()
    {
        return $this->setup;
    }

    /**
     * @param TournamentSetupInterface $setup
     */
    public function setSetup(TournamentSetupInterface $setup)
    {
        $this->setup = $setup;
    }

    /**
     * @return Board[]
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * @param Board[] $boards
     */
    public function setBoards($boards)
    {
        $this->boards = $boards;
    }

    /**
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param Player[] $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
    }

    /**
     * All known rounds, this may extend in a running tournament
     *
     * @return Round[]
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * get a round by number (starting from 0)
     *
     * @param int $number
     *
     * @return Round|null
     */
    public function getRoundByNumber($number)
    {
        foreach ($this->getRounds() as $round) {
            if ($round->getNumber() == $number) {
                return $round;
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->isClosed;
    }

    /**
     * @param bool $isClosed
     */
    public function setClosed($isClosed)
    {
        $this->isClosed = $isClosed;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}
