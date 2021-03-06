<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\Bolts\Arrow;
use Fda\TournamentBundle\Engine\EngineEvents;
use Fda\TournamentBundle\Engine\Events\ArrowEvent;
use Fda\TournamentBundle\Engine\Events\LegEvent;
use Fda\TournamentBundle\Engine\Events\TurnEvent;
use Fda\TournamentBundle\Entity\Leg;
use Fda\TournamentBundle\Entity\Turn;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

abstract class AbstractLegGears implements LegGearsInterface
{
    /** @var Leg */
    protected $leg;

    /** @var LoggerInterface */
    protected $logger;

    /** @var Turn */
    private $turnCompleted;

    /** @var Leg */
    private $legCompleted;

    /**
     * AbstractLegGears constructor.
     * @param Leg $leg
     */
    public function __construct(Leg $leg)
    {
        $this->leg = $leg;
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
     *
     * @return string
     */
    protected function getLogIdentification()
    {
        $className = get_class($this);
        $className = substr($className, strrpos($className, "\\")+1);
//        $className .= sprintf('{%s}', spl_object_hash($this));
//        $className .= sprintf(
//            '(id:%d,no:%d)',
//            $this->round->getId(),
//            $this->round->getNumber()
//        );

        return $className;
    }

    /**
     * register turn as completed
     *
     * if a completed turn is registered, the appropriate event will be dispatched
     * (when the time is right)
     *
     * @param Turn $turn
     */
    protected function setTurnCompleted(Turn $turn)
    {
        $this->turnCompleted = $turn;
    }

    /**
     * register leg as completed
     *
     * if a completed leg is registered, the appropriate event will be dispatched
     * (when the time is right)
     *
     * @param Leg $leg
     */
    protected function setLegCompleted(Leg $leg)
    {
        $this->legCompleted = $leg;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            EngineEvents::ARROW_INCOMING => 'onIncomingArrow',
        );
    }

    /**
     * @param ArrowEvent               $arrowIncomingEvent
     * @param string                   $name
     * @param EventDispatcherInterface $dispatcher
     */
    public function onIncomingArrow(ArrowEvent $arrowIncomingEvent, $name, EventDispatcherInterface $dispatcher)
    {
//        $this->log('onIncomingArrow');

        $incomingArrow = $arrowIncomingEvent->getArrow();
        $arrow = $this->handleArrow(clone $incomingArrow);

        if (null === $arrow) {
            throw new \RuntimeException('LegGears::handleArrow(...) must return an arrow');
        }
        if (null === $arrow->getNumber()) {
            throw new \RuntimeException('handleArrow must set arrow-number!');
        }

        $arrowRegisteredEvent = new ArrowEvent();
        $arrowRegisteredEvent->setArrow($arrow);
        $dispatcher->dispatch(
            EngineEvents::ARROW_REGISTERED,
            $arrowRegisteredEvent
        );

        if (null !== $this->turnCompleted) {
            $turnCompletedEvent = new TurnEvent();
            $turnCompletedEvent->setTurn($this->turnCompleted);
            $dispatcher->dispatch(
                EngineEvents::TURN_COMPLETED,
                $turnCompletedEvent
            );
        }

        if (null !== $this->legCompleted) {
            $legCompletedEvent = new LegEvent();
            $legCompletedEvent->setLeg($this->legCompleted);
            $dispatcher->dispatch(
                EngineEvents::LEG_COMPLETED,
                $legCompletedEvent
            );
        }
    }

    /**
     * handle the provided arrow and set number
     *
     * @param Arrow $arrow
     *
     * @return Arrow
     */
    protected abstract function handleArrow(Arrow $arrow);
}
