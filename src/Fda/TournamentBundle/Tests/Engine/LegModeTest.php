<?php

namespace Fda\TournamentBundle\Tests\Engine;

use Fda\TournamentBundle\Engine\LegMode;

class LegModeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateValidModes()
    {
        new LegMode(LegMode::SINGLE_OUT_301);
        new LegMode(LegMode::DOUBLE_OUT_301);
        new LegMode(LegMode::SINGLE_OUT_501);
        new LegMode(LegMode::DOUBLE_OUT_501);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidMode()
    {
        new LegMode('invalid_555');
    }
}
