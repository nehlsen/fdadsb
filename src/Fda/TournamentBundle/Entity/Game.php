<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Entity\Player;

/**
 * @ORM\Entity
 */
class Game
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
     * @ORM\ManyToOne(targetEntity="Fda\TournamentBundle\Entity\Tournament", inversedBy="games")
     * @var Tournament
     */
    protected $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\BoardBundle\Entity\Board")
     * @var Board
     */
    protected $board;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player", inversedBy="gamesReferred")
     * @var Player
     */
    protected $referee;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player", inversedBy="gamesAsPlayer1")
     * @var Player
     */
    protected $player1;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player", inversedBy="gamesAsPlayer2")
     * @var Player
     */
    protected $player2;

    /**
     * @ORM\OneToMany(
     *  targetEntity="Fda\TournamentBundle\Entity\Leg",
     *  mappedBy="game",
     *  cascade={"persist"}
     * )
     * @var Leg[]
     */
    protected $legs;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    public static function prepare(Player $player1, Player $player2, Tournament $tournament = null)
    {
        if ($player1->getId() == $player2->getId()) {
            throw new \InvalidArgumentException('a player can not play against himself');
        }

        $game = new self();
        $game->player1 = $player1;
        $game->player2 = $player2;
        $game->tournament = $tournament;

        return $game;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @return Player
     */
    public function getReferee()
    {
        return $this->referee;
    }

    /**
     * @return Player
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * @return Player
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * @return Collection|Leg[]
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * @param Leg $leg
     */
    public function addLeg(Leg $leg)
    {
        $this->legs[] = $leg;
    }
}
