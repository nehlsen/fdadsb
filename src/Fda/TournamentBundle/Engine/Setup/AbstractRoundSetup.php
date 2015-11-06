<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\GameMode;
use Fda\TournamentBundle\Engine\LegMode;

abstract class AbstractRoundSetup implements RoundSetupInterface
{
    /** @var int */
    protected $advance = 0;

    /** @var InputInterface */
    protected $input;

    /** @var GameMode */
    protected $gameMode;

    /** @var LegMode */
    protected $legMode;

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
    public function setGameMode(GameMode $mode)
    {
        $this->gameMode = $mode;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getGameMode()
    {
        return $this->gameMode;
    }

    /**
     * @inheritDoc
     */
    public function setLegMode(LegMode $mode)
    {
        $this->legMode = $mode;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLegMode()
    {
        return $this->legMode;
    }
}
