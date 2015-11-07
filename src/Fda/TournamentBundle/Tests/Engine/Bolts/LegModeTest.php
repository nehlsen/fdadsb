<?php

namespace Fda\TournamentBundle\Tests\Engine\Bolts;

use Fda\TournamentBundle\Engine\Bolts\LegMode;

class LegModeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateValidModes()
    {
        $so3 = new LegMode(LegMode::SINGLE_OUT_301);
        $this->assertEquals(LegMode::SINGLE_OUT_301, $so3->getMode());
        $this->assertEquals(301, $so3->getRequiredScore());
        $this->assertFalse($so3->isDoubleOutRequired());

        $do3 = new LegMode(LegMode::DOUBLE_OUT_301);
        $this->assertEquals(LegMode::DOUBLE_OUT_301, $do3->getMode());
        $this->assertEquals(301, $do3->getRequiredScore());
        $this->assertTrue($do3->isDoubleOutRequired());

        $so5 = new LegMode(LegMode::SINGLE_OUT_501);
        $this->assertEquals(LegMode::SINGLE_OUT_501, $so5->getMode());
        $this->assertEquals(501, $so5->getRequiredScore());
        $this->assertFalse($so5->isDoubleOutRequired());

        $do5 = new LegMode(LegMode::DOUBLE_OUT_501);
        $this->assertEquals(LegMode::DOUBLE_OUT_501, $do5->getMode());
        $this->assertEquals(501, $do5->getRequiredScore());
        $this->assertTrue($do5->isDoubleOutRequired());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidMode()
    {
        new LegMode('invalid_555');
    }
}
