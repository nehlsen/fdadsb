<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\EngineEvents;
use Fda\TournamentBundle\Engine\Events\GameEvent;
use Fda\TournamentBundle\Engine\Events\GroupEvent;
use Fda\TournamentBundle\Engine\Events\RoundEvent;
use Fda\TournamentBundle\Engine\Factory\GameGearsFactory;
use Fda\TournamentBundle\Engine\Setup\RoundSetupInterface;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Group;
use Fda\TournamentBundle\Entity\Round;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

abstract class AbstractRoundGears implements RoundGearsInterface
{
    /** @var GameGearsFactory */
    protected $gameGearsFactory;

    /** @var LoggerInterface */
    protected $logger;

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
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * log message to debug log (if logger has been set)
     * @param string $message
     */
    protected function log($message)
    {
        if ($this->logger) {
            $this->logger->debug(sprintf(
                '%s:%s',
                $this->getLogIdentification(),
                $message
            ));
        }
    }

    /**
     * get a string to identify this object in logs
     *
     * the default implementation returns the class name stripped of the namespace
     * with added id and round-number
     *
     * @return string
     */
    protected function getLogIdentification()
    {
        $className = get_class($this);
        $className = substr($className, strrpos($className, "\\")+1);
//        $className .= sprintf('{%s}', spl_object_hash($this));
        $className .= sprintf(
            '(id:%d,no:%d)',
            $this->round->getId(),
            $this->round->getNumber()
        );

        return $className;
    }

    /**
     * @inheritDoc
     */
    public function getRound()
    {
        if (null !== $this->previousRound && !$this->previousRound->isRoundCompleted()) {
            // previous round not complete, initializing this round would probably fail
            return null;
        }

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
    public function isRoundCompleted()
    {
        return $this->getRound()->isClosed();

//        if (null !== $this->round && $this->round->isClosed()) {
//            return true;
//        }
//
//        // leave it to the implementing class
//        return false;
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

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            EngineEvents::GAME_COMPLETED => 'onGameCompleted',
        );
    }

    /**
     * @param GameEvent                $gameCompletedEvent
     * @param string                   $name
     * @param EventDispatcherInterface $dispatcher
     */
    public function onGameCompleted(GameEvent $gameCompletedEvent, $name, EventDispatcherInterface $dispatcher)
    {
        if (!$this->isRoundInitialized) {
            // if this round is not yet initialized, we are probably not meant...
//            $this->log('onGameCompleted, no round initialized - bailing');
            return;
        }

        $game = $gameCompletedEvent->getGame();
        if ($this->getRound()->getId() != $game->getGroup()->getRound()->getId()) {
            $this->log('onGameCompleted, event happened in another round (id mismatch)');
            return;
        }

        $this->log('onGameCompleted, proceed - forward event to implementing class');
        $this->handleGameCompleted($game, $dispatcher);
    }

    /**
     * handle the provided completed game
     *
     * @param Game $game
     * @param EventDispatcherInterface $dispatcher
     */
    protected function handleGameCompleted(Game $game, EventDispatcherInterface $dispatcher)
    {
        $this->handleGameCompletesGroup($game, $dispatcher);
    }

    /**
     * check if the provided game completes the associated group and proceed accordingly
     *
     * @param Game $game
     * @param EventDispatcherInterface $dispatcher
     */
    protected function handleGameCompletesGroup(Game $game, EventDispatcherInterface $dispatcher)
    {
        $group = $game->getGroup();
        if ($group->isClosed()) {
            $this->log('handleGameCompletesGroup, GROUP COMPLETE!');

            $groupCompletedEvent = new GroupEvent();
            $groupCompletedEvent->setGroup($group);
            $dispatcher->dispatch(
                EngineEvents::GROUP_COMPLETED,
                $groupCompletedEvent
            );

            $this->handleGroupCompletesRound($group, $dispatcher);
        }
    }

    /**
     * check if the provided group completes the associated round and proceed accordingly
     *
     * @param Group $group
     * @param EventDispatcherInterface $dispatcher
     */
    protected function handleGroupCompletesRound(Group $group, EventDispatcherInterface $dispatcher)
    {
        $round = $group->getRound();
        if ($round->isClosed()) {
            $this->log('handleGroupCompletesRound, ROUND COMPLETE!');

            $roundCompletedEvent = new RoundEvent();
            $roundCompletedEvent->setRound($round);
            $dispatcher->dispatch(
                EngineEvents::ROUND_COMPLETED,
                $roundCompletedEvent
            );

//            throw new \Exception('without this exception, the game would be persisted and the round would be closed!');
        }
    }
}
