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
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    public function __construct(Game $game)
    {
        $this->game = $game;
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
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
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

    /**
     * @param Player $player
     *
     * @return int
     */
    public function getTotalScoreOf(Player $player)
    {
        $total = 0;

        foreach ($this->getTurnsOf($player) as $turn) {
            $total += $turn->getTotalScore();
        }

        return $total;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}
