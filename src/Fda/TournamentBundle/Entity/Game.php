<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Manager\GameStats;
use Fda\TournamentBundle\Manager\GameStatsFactory;

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
     * @ORM\ManyToOne(
     *     targetEntity="Fda\TournamentBundle\Entity\Group",
     *     inversedBy="games",
     *     cascade={"persist"}
     * )
     * @var Group
     */
    protected $group;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\BoardBundle\Entity\Board", inversedBy="games")
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
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $legsWonPlayer1 = 0;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $legsWonPlayer2 = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player", inversedBy="wonGames")
     * @var Player
     */
    protected $winner;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /** @var GameStats */
    protected $stats;

    public function __construct(Group $group, Player $player1, Player $player2)
    {
        if ($player1->getId() == $player2->getId()) {
            throw new \InvalidArgumentException('a player can not play against himself');
        }

        $this->group = $group;
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->legs = new ArrayCollection();
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
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return \Fda\TournamentBundle\Engine\Bolts\GameMode
     */
    public function getGameMode()
    {
        $setup = $this->getGroup()->getRound()->getSetup();
        return $setup->getGameMode();
    }

    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @param Board $board
     */
    public function setBoard($board)
    {
        $this->board = $board;
    }

    /**
     * @return Player
     */
    public function getReferee()
    {
        return $this->referee;
    }

    /**
     * @param Player $referee
     */
    public function setReferee($referee)
    {
        $this->referee = $referee;
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

    /**
     * @return int
     */
    public function getLegsWonPlayer1()
    {
        return $this->legsWonPlayer1;
    }

    /**
     * @return int
     */
    public function getLegsWonPlayer2()
    {
        return $this->legsWonPlayer2;
    }

    /**
     * @param Player $player
     * @return int
     */
    public function getLegsWonOf(Player $player)
    {
        if ($player == $this->getPlayer1()) {
            return $this->getLegsWonPlayer1();
        } elseif ($player == $this->getPlayer2()) {
            return $this->getLegsWonPlayer2();
        } else {
            return 0;
        }
    }

    /**
     * @param Player $player
     *
     * @return Turn[]
     */
    public function getTurnsOf(Player $player)
    {
        $turns = array();

        foreach ($this->getLegs() as $leg) {
            $turns = array_merge($turns, $leg->getTurnsOf($player)->toArray());
        }

        return $turns;
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        return count($this->getLegs()) > 0;
    }

    /**
     * @return Player
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param Player $winner
     */
    public function setWinner(Player $winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return boolean
     */
    public function isClosed()
    {
        return null !== $this->winner;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    public function updateWonLegs()
    {
        $this->legsWonPlayer1 = 0;
        $this->legsWonPlayer2 = 0;

        foreach ($this->getLegs() as $leg) {
            if (!$leg->isClosed()) {
                continue;
            }

            if ($leg->getWinner() == $this->getPlayer1()) {
                $this->legsWonPlayer1 += 1;
            } elseif ($leg->getWinner() == $this->getPlayer2()) {
                $this->legsWonPlayer2 += 1;
            } else {
                throw new \Exception('the winner of one leg does not belong to this game!');
            }
        }
    }

    /**
     * some more game statistics, another class to not overly bloat the modal ;)
     */
    public function getStats()
    {
        if (null === $this->stats) {
            $this->stats = GameStatsFactory::create($this);
        }

        return $this->stats;
    }
}
