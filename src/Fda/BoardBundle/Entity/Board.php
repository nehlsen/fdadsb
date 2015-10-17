<?php

namespace Fda\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Tournament;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Board
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
     *      mapping="board_image",
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
     * @ORM\ManyToMany(targetEntity="Fda\TournamentBundle\Entity\Tournament",mappedBy="boards")
     * @var Tournament[]
     */
    protected $tournaments;

    /**
     * @ORM\OneToMany(targetEntity="Fda\TournamentBundle\Entity\Game", mappedBy="board")
     * @var Game[]
     */
    protected $games;

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
    public function getGames()
    {
        return $this->games;
    }
}
