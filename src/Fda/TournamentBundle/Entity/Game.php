<?php

namespace Fda\TournamentBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="Fda\TournamentBundle\Entity\Tournament")
     * @var Tournament
     */
    protected $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\BoardBundle\Entity\Board")
     * @var Board
     */
    protected $board;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player")
     * @var Player
     */
    protected $referee;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player")
     * @var Player
     */
    protected $player1;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player")
     * @var Player
     */
    protected $player2;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Leg", mappedBy="game")
     * @var Leg[]
     */
    protected $legs;
}
