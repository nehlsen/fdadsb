<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Bolts\Arrow;

/**
 * @ORM\Entity
 */
class Turn
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
     * @ORM\ManyToOne(targetEntity="Fda\TournamentBundle\Entity\Leg", inversedBy="turns")
     * @var Leg
     */
    protected $leg;

    /**
     * @ORM\ManyToOne(targetEntity="Fda\PlayerBundle\Entity\Player", inversedBy="turnsCompleted")
     * @var Player
     */
    protected $player;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $arrow1multiplier = Arrow::MULTIPLIER_SINGLE;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow1score = -1;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow1total = -1;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $arrow2multiplier = Arrow::MULTIPLIER_SINGLE;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow2score = -1;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow2total = -1;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $arrow3multiplier = Arrow::MULTIPLIER_SINGLE;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow3score = -1;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $arrow3total = -1;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $totalScore = 0;

    /**
     * mark this turn as void (e.g. failed to double-out)
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected $isVoid = false;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    public function __construct(Leg $leg, Player $player)
    {
        $this->leg = $leg;
        $this->player = $player;
        $this->created = new \DateTime();

        $leg->addTurn($this);
    }

    /**
     * THIS IS ONLY FOR TESTING!
     */
    public function __toString()
    {
        $dmp = $this->getPlayer()->getName();
        $dmp .= ': ';

        foreach ([1,2,3] as $num) {
            $dmp .= ($num>1?', ':'').$num.':';
            if ($this->hasArrow($num)) {
                $arrow = $this->getArrow($num);
                $dmp .= $arrow->getMultiplier().'-'.$arrow->getScore().'='.$arrow->getTotal();
            } else {
                $dmp .= '/';
            }
        }

        $dmp .= '['.($this->isVoid() ? 'VOID' : 'OK').']';

        return $dmp;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Leg
     */
    public function getLeg()
    {
        return $this->leg;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param int $number
     * @return bool
     */
    public function hasArrow($number)
    {
        switch ($number) {
            case 1:
                return $this->arrow1score !== null && $this->arrow1score >= 0;
                break;
            case 2:
                return $this->arrow2score !== null && $this->arrow2score >= 0;
                break;
            case 3:
                return $this->arrow3score !== null && $this->arrow3score >= 0;
                break;
        }

        return false;
    }

    /**
     * @param int $number
     *
     * @return Arrow
     */
    public function getArrow($number)
    {
        $score = 0;
        $multiplier = Arrow::MULTIPLIER_SINGLE;

        switch ($number) {
            case 1:
                $score = $this->arrow1score;
                $multiplier = $this->arrow1multiplier;
                break;
            case 2:
                $score = $this->arrow2score;
                $multiplier = $this->arrow2multiplier;
                break;
            case 3:
                $score = $this->arrow3score;
                $multiplier = $this->arrow3multiplier;
                break;
        }

        $arrow = Arrow::create($score, $multiplier);
        $arrow->setNumber($number);

        return $arrow;
    }

    /**
     * @return Arrow|null
     */
    public function getLastArrow()
    {
        foreach ([3,2,1] as $number) {
            if ($this->hasArrow($number)) {
                return $this->getArrow($number);
            }
        }

        return null;
    }

    /**
     * @return Arrow[]
     */
    public function getArrows()
    {
        $arrows = array();

        foreach ([3,2,1] as $number) {
            if ($this->hasArrow($number)) {
                $arrows[] = $this->getArrow($number);
            }
        }

        return $arrows;
    }

    /**
     * @param Arrow $arrow
     *
     * @return Arrow
     */
    public function setArrow(Arrow $arrow)
    {
        switch ($arrow->getNumber()) {
            case 1:
                $this->arrow1multiplier = $arrow->getMultiplier();
                $this->arrow1score = $arrow->getScore();
                $this->arrow1total = $arrow->getTotal();
                break;
            case 2:
                $this->arrow2multiplier = $arrow->getMultiplier();
                $this->arrow2score = $arrow->getScore();
                $this->arrow2total = $arrow->getTotal();
                break;
            case 3:
                $this->arrow3multiplier = $arrow->getMultiplier();
                $this->arrow3score = $arrow->getScore();
                $this->arrow3total = $arrow->getTotal();
                break;
        }

        if ($this->isVoid()) {
            $this->totalScore = 0;
        } else {
            $this->totalScore =
                ($this->arrow1total > 0 ? $this->arrow1total : 0) +
                ($this->arrow2total > 0 ? $this->arrow2total : 0) +
                ($this->arrow3total > 0 ? $this->arrow3total : 0);
        }

        return $arrow;
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        if (!$this->hasArrow(1)) {
            return false;
        }
        if (!$this->hasArrow(2)) {
            return false;
        }
        if (!$this->hasArrow(3)) {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getTotalScore()
    {
        return $this->totalScore;
    }

    /**
     * @return double
     */
    public function getAverageScore()
    {
        $arrowCount = 0;
        if ($this->hasArrow(1)) {
            $arrowCount = 1;
        }
        if ($this->hasArrow(2)) {
            $arrowCount = 2;
        }
        if ($this->hasArrow(3)) {
            $arrowCount = 3;
        }

        return 0 == $arrowCount ? 0.0 : $this->totalScore / ($arrowCount*1.0);
    }

    /**
     * @return bool
     */
    public function isVoid()
    {
        return $this->isVoid;
    }

    /**
     * @param bool|true $isVoid
     */
    public function setVoid($isVoid = true)
    {
        $this->isVoid = $isVoid;
    }
}
