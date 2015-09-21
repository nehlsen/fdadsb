<?php

namespace Fda\TournamentBundle\Engine;

class EngineAware
{
    /** @var EngineInterface */
    protected $engine;

    /**
     * @inheritDoc
     */
    public function setEngine(EngineInterface $engine)
    {
        $this->engine = $engine;
    }
}
