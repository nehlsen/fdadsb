<?php

namespace Fda\TournamentBundle\Engine\Setup;

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
}
