<?php

namespace Fda\TournamentBundle\Engine\Bolts;

/**
 * how to win a leg
 */
final class LegMode
{
    const SINGLE_OUT_301 = 'so_301';
    const DOUBLE_OUT_301 = 'do_301';
    const SINGLE_OUT_501 = 'so_501';
    const DOUBLE_OUT_501 = 'do_501';

    /** @var string */
    protected $mode;

    public function __construct($mode)
    {
        $this->mode = $this->checkMode($mode);
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
        if (!in_array($mode, array(
            self::SINGLE_OUT_301, self::DOUBLE_OUT_301,
            self::SINGLE_OUT_501, self::DOUBLE_OUT_501
        ))) {
            throw new \InvalidArgumentException('invalid mode');
        }

        return $mode;
    }
}
