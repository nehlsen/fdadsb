<?php

namespace Fda\TournamentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fda\TournamentBundle\Engine\Setup\RoundSetupInterface;

/**
 * @ORM\Entity
 */
class Round
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
     *     targetEntity="Fda\TournamentBundle\Entity\Tournament",
     *     inversedBy="rounds"
     * )
     * @var Tournament
     */
    protected $tournament;

    /**
     * round number starting at 0 for the seed round
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $number;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Fda\TournamentBundle\Entity\Group",
     *     mappedBy="round",
     *     cascade={"persist"}
     * )
     * @var Group[]
     */
    protected $groups;

    /**
     * Round constructor.
     * @param Tournament $tournament
     * @param int $roundNumber
     */
    public function __construct(Tournament $tournament, $roundNumber)
    {
        $this->tournament = $tournament;
        $this->number = $roundNumber;
        $this->groups = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @return RoundSetupInterface
     */
    public function getSetup()
    {
        $roundsSetup = $this->getTournament()->getSetup()->getRounds();

        // FIXME
        $roundNumber = $this->getNumber() - 1;

        if (!array_key_exists($roundNumber, $roundsSetup)) {
            throw new \RuntimeException(sprintf('failed to find setup for round %d', $roundNumber));
        }

        return $roundsSetup[$roundNumber];
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return Group[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param int $groupNumber
     * @return Group|null
     */
    public function getGroupByNumber($groupNumber)
    {
        foreach ($this->getGroups() as $group) {
            if ($group->getNumber() == $groupNumber) {
                return $group;
            }
        }

        return null;
    }

    /**
     * create a group, associate it with this round and return it
     *
     * @param int $groupNumber
     * @return Group
     */
    public function createGroup($groupNumber)
    {
        if (null !== $this->getGroupByNumber($groupNumber)) {
            throw new \RuntimeException(sprintf(
                'a group with group-number %d already exists in this round',
                $groupNumber
            ));
        }

        $group = new Group($this, $groupNumber);
        $this->groups[] = $group;
        return $group;
    }
}
