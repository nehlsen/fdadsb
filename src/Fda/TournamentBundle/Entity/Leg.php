<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Fda\PlayerBundle\Entity\Player;

/**
 * @ORM\Entity
 */
class Leg
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
     *  targetEntity="Fda\TournamentBundle\Entity\Game",
     *  inversedBy="legs"
     * )
     * @var Game
     */
    protected $game;

    /**
     * @ORM\OneToMany(
     *  targetEntity="Fda\TournamentBundle\Entity\Turn",
     *  mappedBy="leg",
     *  cascade={"persist"}
     * )
     * @var Turn[]
     */
    protected $turns;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $player1score = 0;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $player1shots = 0;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $player2score = 0;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $player2shots = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player", inversedBy="wonLegs")
     * @var Player
     */
    protected $winner;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->created = new \DateTime();

        $game->addLeg($this);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return \Fda\TournamentBundle\Engine\Bolts\LegMode
     */
    public function getLegMode()
    {
        $setup = $this->getGame()->getGroup()->getRound()->getSetup();
        return $setup->getLegMode();
    }

    /**
     * @return Turn[]
     */
    public function getTurns()
    {
        return $this->turns;
    }

    /**
     * @param Turn $turn
     */
    public function addTurn(Turn $turn)
    {
        $this->turns[] = $turn;
    }

    /**
     * @param Player $player
     *
     * @return Collection|Turn[]
     */
    public function getTurnsOf(Player $player)
    {
        $turns = new ArrayCollection();

        foreach ($this->getTurns() as $turn) {
            if ($turn->getPlayer()->getId() == $player->getId()) {
                $turns[] = $turn;
            }
        }

        return $turns;
    }

//    /**
//     * @param Player $player
//     *
//     * @return int
//     */
//    public function getTotalScoreOf(Player $player)
//    {
//        $total = 0;
// check for turn:isVoid!
//        foreach ($this->getTurnsOf($player) as $turn) {
//            $total += $turn->getTotalScore();
//        }
//
//        return $total;
//    }

    /**
     * @return int
     */
    public function getPlayer1score()
    {
        return $this->player1score;
    }

    /**
     * @return int
     */
    public function getPlayer1shots()
    {
        return $this->player1shots;
    }

    /**
     * @return int
     */
    public function getPlayer2score()
    {
        return $this->player2score;
    }

    /**
     * @return int
     */
    public function getPlayer2shots()
    {
        return $this->player2shots;
    }

    /**
     * @param Player $player
     * @return int
     */
    public function getScoreOf(Player $player)
    {
        if ($player == $this->getGame()->getPlayer1()) {
            return $this->getPlayer1score();
        } elseif ($player == $this->getGame()->getPlayer2()) {
            return $this->getPlayer2score();
        } else {
            return 0;
        }
    }

    /**
     * @param Player $player
     * @return int
     */
    public function getCountShotsOf(Player $player)
    {
        if ($player == $this->getGame()->getPlayer1()) {
            return $this->getPlayer1shots();
        } elseif ($player == $this->getGame()->getPlayer2()) {
            return $this->getPlayer2shots();
        } else {
            return 0;
        }
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
     * does what is says... no parameters, nothing to return...
     */
    public function updateScoresAndShots()
    {
        $this->player1score = 0;
        $this->player1shots = 0;
        $this->player2score = 0;
        $this->player2shots = 0;

        foreach ($this->getTurns() as $turn) {
            if ($turn->isVoid()) {
                continue;
            }

            if ($turn->getPlayer() == $this->getGame()->getPlayer1()) {
                $this->player1score += $turn->getTotalScore();
                $this->player1shots += 3;
            } else {
                $this->player2score += $turn->getTotalScore();
                $this->player2shots += 3;
            }
        }
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}
