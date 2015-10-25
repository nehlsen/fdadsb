<?php

namespace Fda\TournamentBundle\Entity;

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
     *  targetEntity="Fda\TournamentBundle\Entity\Game",
     *  mappedBy="tournament",
     *  cascade={"persist"}
     * )
     * @var Game[]
     */
    protected $games;

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
     * All known games, this may extend in a running tournament
     *
     * @return Game[]
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * get closed games
     *
     * @return Game[]
     */
    public function getClosedGames()
    {
        $games = array();

        foreach($this->getGames() as $game) {
            if (!$game->isClosed()) {
                continue;
            }

            $games[] = $game;
        }

        return $games;
    }

    /**
     * get begun but not yet closed games
     *
     * @return Game[]
     */
    public function getRunningGames()
    {
        $games = array();

        foreach($this->getGames() as $game) {
            if ($game->isClosed()) {
                continue;
            }
            if (!$game->isStarted()) {
                continue;
            }

            $games[] = $game;
        }

        return $games;
    }

    /**
     * get not closed games
     *
     * @param bool $excludeRunning whether to exclude begun games
     *
     * @return Game[]
     */
    public function getOpenGames($excludeRunning = true)
    {
        $games = array();

        foreach($this->getGames() as $game) {
            if ($game->isClosed()) {
                continue;
            }
            if ($excludeRunning && $game->isStarted()) {
                continue;
            }

            $games[] = $game;
        }

        return $games;
    }

    /**
     * get tournament scoreboard as games-won to player-ID
     * @return array win-count => player-ID
     */
    public function getGamesWonToPlayer()
    {
        $playerToWon = array();

        foreach ($this->games as $game) {
            $winner = $game->getWinner();
            if (null === $winner) {
                continue;
            }

            if (!array_key_exists($winner->getId(), $playerToWon)) {
                $playerToWon[$winner->getId()] = 0;
            }

            $playerToWon[$winner->getId()] += 1;
        }

        $wonToPlayer = array_flip($playerToWon);
        ksort($wonToPlayer);
        return array_reverse($wonToPlayer, true);
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
