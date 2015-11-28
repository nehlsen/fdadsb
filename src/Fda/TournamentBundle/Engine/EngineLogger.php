<?php

namespace Fda\TournamentBundle\Engine;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class EngineLogger implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            EngineEvents::ARROW_INCOMING => ['onEngineEvent', 100],
            EngineEvents::ARROW_REGISTERED => ['onEngineEvent', 100],
            EngineEvents::TURN_COMPLETED => ['onEngineEvent', 100],
            EngineEvents::LEG_COMPLETED => ['onEngineEvent', 100],
            EngineEvents::GAME_COMPLETED => ['onEngineEvent', 100],
            EngineEvents::GROUP_COMPLETED => ['onEngineEvent', 100],
            EngineEvents::ROUND_COMPLETED => ['onEngineEvent', 100],
            EngineEvents::TOURNAMENT_COMPLETED => ['onEngineEvent', 100],
        );
    }

    public function onEngineEvent(Event $event, $name, EventDispatcherInterface $dispatcher)
    {
        $this->logger->info(sprintf(
            'FDA:TournamentEngine: event "%s" just happened!',
            $name
        ));
    }
}
