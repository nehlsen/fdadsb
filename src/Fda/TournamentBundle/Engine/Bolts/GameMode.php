<?php

namespace Fda\TournamentBundle\Engine\Bolts;

/**
 * how to win a game
 */
final class GameMode
{
    /** first who wins 3 legs wins games */
    const FIRST_TO = 'first_to';

    /** first to have 3 more legs than contestant */
    const AHEAD    = 'ahead';

    /** @var string */
    protected $mode;

    /** @var int */
    protected $count;

    public function __construct($mode, $count)
    {
        $this->mode = $this->checkMode($mode);
        $this->count = $this->checkCount($count);
    }

    // for debug only!
    public function __toString()
    {
        return $this->getMode().':'.$this->getCount();
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * check if mode is valid and return it
     *
     * @param string $mode
     * @return string
     * @throws \InvalidArgumentException if mode is invalid
     */
    protected function checkMode($mode)
    {
        if (!in_array($mode, array(self::FIRST_TO, self::AHEAD))) {
            throw new \InvalidArgumentException('invalid mode');
        }

        return $mode;
    }

    /**
     * check if count is valid and return it
     *
     * @param int $count
     * @return int
     * @throws \InvalidArgumentException if count is invalid
     */
    protected function checkCount($count)
    {
        $count = (int)$count;
        if ($count < 1) {
            throw new \InvalidArgumentException('invalid count');
        }

        return $count;
    }
}
