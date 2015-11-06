<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fda\PlayerBundle\Entity\Player;

/**
 * @ORM\Entity
 * @ORM\Table(name="RoundGroup")
 */
class Group
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
     * @ORM\ManyToOne(
     *     targetEntity="Fda\TournamentBundle\Entity\Round",
     *     inversedBy="groups"
     * )
     * @var Round
     */
    protected $round;

    /**
     * round number starting at 0 for the seed round
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $number;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Fda\PlayerBundle\Entity\Player",
     *     mappedBy="groups",
     *     cascade={"persist"}
     * )
     * @var Player[]
     */
    protected $players;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Fda\TournamentBundle\Entity\Game",
     *     mappedBy="group",
     *     cascade={"persist"}
     * )
     * @var Game[]
     */
    protected $games;

    /**
     * Group constructor.
     * @param Round $round
     * @param int $groupNumber
     */
    public function __construct(Round $round, $groupNumber)
    {
        $this->round = $round;
        $this->number = $groupNumber;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Round
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
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
     * @return Game[]
     */
    public function getGames()
    {
        return $this->games;
    }
}
