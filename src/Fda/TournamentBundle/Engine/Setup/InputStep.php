<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;

class InputStep implements InputInterface
{
    /** @var int */
    protected $steps = 1;

    /**
     * InputStep constructor.
     * @param int $steps
     */
    public function __construct($steps = 1)
    {
        $this->steps = $steps;
    }

    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'step';
    }

    /**
     * @return int
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param int $steps
     * @return InputStep this
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function filter(RoundGearsInterface $previousRoundGears)
    {
        // TODO implement input step : filter
        throw new \Exception('TODO');
    }
}
