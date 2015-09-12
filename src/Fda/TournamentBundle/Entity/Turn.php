<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fda\PlayerBundle\Entity\Player;

/**
 * @ORM\Entity
 */
class Turn
{
    const MULTIPLIER_SINGLE = 'single';
    const MULTIPLIER_DOUBLE = 'double';
    const MULTIPLIER_TRIPLE = 'triple';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\TournamentBundle\Entity\Leg")
     * @var Leg
     */
    protected $leg;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player")
     * @var Player
     */
    protected $player;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $arrow1multiplier;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow1hit;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow1score;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $arrow2multiplier;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow2hit;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow2score;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $arrow3multiplier;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow3hit;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow3score;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $totalScore;
}
