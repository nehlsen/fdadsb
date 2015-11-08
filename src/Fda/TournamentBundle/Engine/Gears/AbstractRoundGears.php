<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\Factory\GameGearsFactory;
use Fda\TournamentBundle\Engine\Setup\RoundSetupInterface;
use Fda\TournamentBundle\Entity\Group;
use Fda\TournamentBundle\Entity\Round;

abstract class AbstractRoundGears implements RoundGearsInterface
{
    /** @var GameGearsFactory */
    protected $gameGearsFactory;

    /** @var Round */
    private $round;

    /** @var bool */
    private $isRoundInitialized = false;

    /** @var RoundSetupInterface */
    protected $setup;

    /** @var RoundGearsInterface */
    protected $previousRound;

    /** @var GameGearsInterface[] */
    private $gameGears;

    public function __construct(Round $round, RoundSetupInterface $setup)
    {
        $this->round = $round;
        $this->setup = $setup;
    }

    /**
     * @inheritDoc
     */
    public function setGameGearsFactory(GameGearsFactory $gameGearsFactory)
    {
        $this->gameGearsFactory = $gameGearsFactory;
    }

    /**
     * @inheritDoc
     */
    public function getRound()
    {
        if (!$this->isRoundInitialized) {
            $this->initializeRoundEntity($this->round);
            $this->isRoundInitialized = true;
        }

        return $this->round;
    }

    /**
     * @inheritDoc
     */
    public function getSetup()
    {
        return $this->setup;
    }

    /**
     * this will be called upon first request to the round-entity(-getter)
     * sub classes should implement this function and should initialize the round-entity
     *  with group-entities and game-entities
     *
     * the default implementation uses the configured input filter to initialize the groups and games
     *
     * @param Round $round as the round is private and should only be accessed via getter it is provided as parameter
     */
    protected function initializeRoundEntity(Round $round)
    {
        $inputFilter = $this->setup->getInput();
        $playersGrouped = $inputFilter->filter($this->previousRound);

        foreach ($playersGrouped as $groupNumber => $players) {
            $group = $round->getGroupByNumber($groupNumber);
            if (null === $group) {
                $group = $round->createGroup($groupNumber); // this could create the game-entities too...
            }

            $group->setPlayers($players);
        }
    }

    /**
     * @inheritDoc
     */
    public function setPreviousRoundGears(RoundGearsInterface $previousRound)
    {
        $this->previousRound = $previousRound;
    }

    /**
     * @inheritDoc
     */
    public function getPreviousRoundGears()
    {
        return $this->previousRound;
    }

    /**
     * @inheritDoc
     */
    public function hasPreviousRoundGears()
    {
        return null !== $this->getPreviousRoundGears();
    }

    /**
     * @inheritDoc
     */
    public function isRoundClosed()
    {
        // at least the previous round has to be closed for this round to be open
        //  whether this round is closed because all matches are complete has to be
        //  determined in implementing sub classes

        if (null === $this->previousRound) {
            throw new \RuntimeException('no previous round set');
        }

        return !$this->previousRound->isRoundClosed();
    }

    /**
     * initialize game-gears (one for each group)
     */
    protected function initGameGearsForAllGroups()
    {
        $this->gameGears = array();

        foreach ($this->getRound()->getGroups() as $group) {
            $gearsForGroup = $this->initGameGearsForGroup($group);

            $this->gameGears[$group->getNumber()] = $gearsForGroup;
        }
    }

    /**
     * initialize game-gears (one for each group)
     *
     * @param Group $group group to create games for
     *
     * @return GameGearsInterface[] a list of game(-gears) which are required for this round and group
     */
    protected abstract function initGameGearsForGroup(Group $group);

    /**
     * @inheritDoc
     */
    public function getGameGearsGrouped()
    {
        if (null === $this->gameGears) {
            $this->initGameGearsForAllGroups();
        }

        return $this->gameGears;
    }

    /**
     * @inheritDoc
     */
    public function getGameGearsForGroup(Group $group)
    {
        $grouped = $this->getGameGearsGrouped();
        if (!array_key_exists($group->getNumber(), $grouped)) {
            throw new \InvalidArgumentException();
        }

        return $grouped[$group->getNumber()];
    }

    /**
     * @inheritDoc
     */
    public function getLeaderBoard()
    {
        // TODO: Implement getLeaderBoard() method.
        throw new \Exception(
            'TODO: Implement getLeaderBoard() of '.get_class($this)
        );
    }
}
