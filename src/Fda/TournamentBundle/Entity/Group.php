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
     *     inversedBy="groups",
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

    /**
     * find a game having player1 and player2 (not necessarily in that particular order)
     *
     * @param Player $player1
     * @param Player $player2
     *
     * @return Game|null
     *
     * @throws \RuntimeException if multiple games match
     */
    public function getGameByContestants(Player $player1, Player $player2)
    {
        $foundGame = null;

        if (null === $this->getGames()) {
            return $foundGame;
        }

        $gameHasPlayer = function (Game $game, Player $player) {
            if ($game->getPlayer1()->getId() == $player->getId()) {
                return true;
            }

            if ($game->getPlayer2()->getId() == $player->getId()) {
                return true;
            }

            return false;
        };

        foreach ($this->getGames() as $game) {
            if (!$gameHasPlayer($game, $player1)) {
                continue;
            }
            if (!$gameHasPlayer($game, $player2)) {
                continue;
            }

            if (null !== $foundGame) {
                throw new \RuntimeException('can not determine what game you asked for, multiple games matched!');
            }

            $foundGame = $game;
        }

        return $foundGame;
    }

    /**
     * whether all games in this group are closed
     * @return bool
     */
    public function isClosed()
    {
        if (null === $this->getGames() && null !== $this->getPlayers() && count($this->getPlayers()) > 0) {
            // no games, probably not initialized yet
            //  we have players, so there should be games too
            return false;
        }

        foreach ($this->getGames() as $game) {
            if (!$game->isClosed()) {
                return false;
            }
        }

        return true;
    }
}
