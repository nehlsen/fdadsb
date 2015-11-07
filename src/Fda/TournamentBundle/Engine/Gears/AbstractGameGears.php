<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\EngineEvents;
use Fda\TournamentBundle\Engine\Events\GameEvent;
use Fda\TournamentBundle\Engine\Events\LegEvent;
use Fda\TournamentBundle\Engine\Factory\LegGearsFactory;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Leg;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractGameGears implements GameGearsInterface
{
    /** @var Game */
    private $game;

    /** @var LegGearsFactory */
    protected $legGearsFactory;

    /** @var LegGearsInterface[] */
//    private $legGears;

    /** @var Game */
    private $gameCompleted;

    public function __construct(Game $game)
    {
        // game provides access to group, round, tournament, setup, players, etc...

        $this->game = $game;
    }

    /**
     * @inheritDoc
     */
    public function setLegGearsFactory(LegGearsFactory $legGearsFactory)
    {
        $this->legGearsFactory = $legGearsFactory;
    }

    /**
     * register game as completed
     *
     * if a completed game is registered, the appropriate event will be dispatched
     * (when the time is right)
     *
     * @param Game $game
     */
    protected function setGameCompleted(Game $game)
    {
        $this->gameCompleted = $game;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            EngineEvents::LEG_COMPLETED => 'onLegCompleted',
        );
    }

    /**
     * @param LegEvent                 $legCompletedEvent
     * @param string                   $name
     * @param EventDispatcherInterface $dispatcher
     */
    public function onLegCompleted(LegEvent $legCompletedEvent, $name, EventDispatcherInterface $dispatcher)
    {
        $leg = $legCompletedEvent->getLeg();
        $this->handleLegCompleted($leg);

        if (null !== $this->gameCompleted) {
            $gameCompletedEvent = new GameEvent();
            $gameCompletedEvent->setGame($this->gameCompleted);
            $dispatcher->dispatch(
                EngineEvents::GAME_COMPLETED,
                $gameCompletedEvent
            );
        }
    }

    /**
     * handle the provided completed leg
     *
     * @param Leg $leg
     */
    protected abstract function handleLegCompleted(Leg $leg);

    /**
     * @inheritDoc
     */
    public function getGame()
    {
        return $this->game;
    }
}
