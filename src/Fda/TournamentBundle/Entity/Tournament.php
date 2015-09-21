<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Entity\Player;

/**
 * @ORM\Entity
 */
class Tournament
{
    // how to advance in and win a tournament
    const TOURNAMENT_LADDER     = 'ladder';
    const TOURNAMENT_ALL_VS_ALL = 'all_vs_all';

    // how to win a game
    const GAME_FIRST_TO = 'first_to'; // first who wins 3 legs wins games
    const GAME_AHEAD    = 'ahead';    // first to have 3 more legs than contestant

    // how to win a leg
    const LEG_301            = 'mode301';
    const LEG_301_DOUBLE_OUT = 'mode301do';
    const LEG_501            = 'mode501';
    const LEG_501_DOUBLE_OUT = 'mode501do';

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
     * @ORM\Column(type="string")
     * @var string
     */
    protected $tournamentMode;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $tournamentCount;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $gameMode;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $gameCount;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $legMode;

    /**
     * @ORM\ManyToMany(targetEntity="Fda\BoardBundle\Entity\Board")
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
        $this->loadDefaults();
        $this->created = new \DateTime();
    }

    protected function loadDefaults()
    {
        $this->setTournamentMode(self::TOURNAMENT_LADDER);
        $this->setTournamentCount(2);
        $this->setGameMode(self::GAME_FIRST_TO);
        $this->setGameCount(5);
        $this->setLegMode(self::LEG_501_DOUBLE_OUT);
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
     * @return string
     */
    public function getTournamentMode()
    {
        return $this->tournamentMode;
    }

    /**
     * @param string $tournamentMode
     */
    public function setTournamentMode($tournamentMode)
    {
        if (!in_array($tournamentMode, $this->getTournamentModes())) {
            throw new \InvalidArgumentException();
        }

        $this->tournamentMode = $tournamentMode;
    }

    /**
     * @return array
     */
    public static function getTournamentModes()
    {
        return array(
            self::TOURNAMENT_ALL_VS_ALL,
            self::TOURNAMENT_LADDER,
        );
    }

    /**
     * @return int
     */
    public function getTournamentCount()
    {
        return $this->tournamentCount;
    }

    /**
     * @param int $tournamentCount
     */
    public function setTournamentCount($tournamentCount)
    {
        $this->tournamentCount = $tournamentCount;
    }

    /**
     * @return string
     */
    public function getGameMode()
    {
        return $this->gameMode;
    }

    /**
     * @param string $gameMode
     */
    public function setGameMode($gameMode)
    {
        if (!in_array($gameMode, $this->getGameModes())) {
            throw new \InvalidArgumentException();
        }

        $this->gameMode = $gameMode;
    }

    /**
     * @return array
     */
    public static function getGameModes()
    {
        return array(
            self::GAME_FIRST_TO,
            self::GAME_AHEAD,
        );
    }

    /**
     * @return int
     */
    public function getGameCount()
    {
        return $this->gameCount;
    }

    /**
     * @param int $gameCount
     */
    public function setGameCount($gameCount)
    {
        $this->gameCount = $gameCount;
    }

    /**
     * @return string
     */
    public function getLegMode()
    {
        return $this->legMode;
    }

    /**
     * @param string $legMode
     */
    public function setLegMode($legMode)
    {
        if (!in_array($legMode, $this->getLegModes())) {
            throw new \InvalidArgumentException();
        }
        $this->legMode = $legMode;
    }

    /**
     * @return array
     */
    public static function getLegModes()
    {
        return array(
            self::LEG_301,
            self::LEG_301_DOUBLE_OUT,
            self::LEG_501,
            self::LEG_501_DOUBLE_OUT,
        );
    }

    /**
     * @return \Fda\BoardBundle\Entity\Board[]
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * @param \Fda\BoardBundle\Entity\Board[] $boards
     */
    public function setBoards($boards)
    {
        $this->boards = $boards;
    }

    /**
     * @return \Fda\PlayerBundle\Entity\Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param \Fda\PlayerBundle\Entity\Player[] $players
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
