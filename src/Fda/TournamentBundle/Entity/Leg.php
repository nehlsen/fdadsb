<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="Fda\TournamentBundle\Entity\Game")
     * @var Game
     */
    protected $game;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Turn", mappedBy="leg")
     * @var Turn[]
     */
    protected $turns;
}
