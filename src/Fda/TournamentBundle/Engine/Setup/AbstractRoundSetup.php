<?php

namespace Fda\TournamentBundle\Engine\Setup;

abstract class AbstractRoundSetup implements RoundSetupInterface
{
    /** @var int */
    protected $advance = 0;

    /** @var InputInterface */
    protected $input;

    /**
     * children should implement factories like createXyz()
     */
    protected function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function setAdvance($number)
    {
        $this->advance = $number;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAdvance()
    {
        return $this->advance;
    }

    /**
     * @param InputInterface $input
     * @return $this
     */
    protected function setInput(InputInterface $input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return $this->input;
    }
}
