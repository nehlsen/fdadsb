<?php

namespace Fda\PlayerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Group;
use Fda\TournamentBundle\Entity\Leg;
use Fda\TournamentBundle\Entity\Tournament;
use Fda\TournamentBundle\Entity\Turn;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Player
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
     * @Vich\UploadableField(
     *      mapping="player_image",
     *      fileNameProperty="imageName"
     * )
     *
     * @var File
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $imageName = '';

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $updated;

    /**
     * @ORM\ManyToMany(targetEntity="Fda\TournamentBundle\Entity\Tournament", mappedBy="players")
     * @var Tournament[]
     */
    protected $tournaments;

    /**
     * @ORM\ManyToMany(targetEntity="Fda\TournamentBundle\Entity\Group", mappedBy="players")
     * @var Group[]
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Game", mappedBy="referee")
     * @var Game[]
     */
    protected $gamesReferred;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Game", mappedBy="player1")
     * @var Game[]
     */
    protected $gamesAsPlayer1;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Game", mappedBy="player2")
     * @var Game[]
     */
    protected $gamesAsPlayer2;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Turn", mappedBy="player")
     * @var Turn[]
     */
    protected $turnsCompleted;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Leg", mappedBy="winner")
     * @var Leg[]
     */
    protected $wonLegs;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Game", mappedBy="winner")
     * @var Game[]
     */
    protected $wonGames;

    /**
     * Player constructor.
     */
    public function __construct()
    {
        $this->updated = new \DateTime();
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
     * @param File|UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updated = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @return Tournament[]
     */
    public function getTournaments()
    {
        return $this->tournaments;
    }

    /**
     * @return Game[]
     */
    public function getGamesReferred()
    {
        return $this->gamesReferred;
    }

    /**
     * @return Game[]
     */
    public function getGames()
    {
        return array_merge(
            $this->gamesAsPlayer1->toArray(),
            $this->gamesAsPlayer2->toArray()
        );
    }

    /**
     * @return Turn[]
     */
    public function getTurns()
    {
        return $this->turnsCompleted;
    }

    /**
     * @return Leg[]
     */
    public function getWonLegs()
    {
        return $this->wonLegs;
    }

    /**
     * @return Game[]
     */
    public function getWonGames()
    {
        return $this->wonGames;
    }
}
