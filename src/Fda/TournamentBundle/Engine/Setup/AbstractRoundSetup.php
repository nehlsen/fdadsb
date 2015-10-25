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
     * @param InputInterface $input
     * @return $this
     */
    protected function setInput(InputInterface $input)
    {
        $this->input = $input;
        return $this;
    }
}
