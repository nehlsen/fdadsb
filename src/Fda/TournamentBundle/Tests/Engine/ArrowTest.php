<?php

namespace Fda\TournamentBundle\Tests\Engine;

use Fda\TournamentBundle\Engine\Arrow;

class ArrowTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $single = new Arrow(1, 5, Arrow::MULTIPLIER_SINGLE);
        $this->assertEquals('5', $single->__toString());

        $double = new Arrow(2, 5, Arrow::MULTIPLIER_DOUBLE);
        $this->assertEquals('D5', $double->__toString());

        $triple = new Arrow(3, 5, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals('T5', $triple->__toString());
    }

    public function testIser()
    {
        $single = new Arrow(1, 5, Arrow::MULTIPLIER_SINGLE);
        $this->assertTrue($single->isSingle());
        $this->assertFalse($single->isDouble());
        $this->assertFalse($single->isTriple());

        $double = new Arrow(2, 5, Arrow::MULTIPLIER_DOUBLE);
        $this->assertFalse($double->isSingle());
        $this->assertTrue($double->isDouble());
        $this->assertFalse($double->isTriple());

        $triple = new Arrow(3, 5, Arrow::MULTIPLIER_TRIPLE);
        $this->assertFalse($triple->isSingle());
        $this->assertFalse($triple->isDouble());
        $this->assertTrue($triple->isTriple());
    }

    public function testToTotals()
    {
        $single = new Arrow(1, 5, Arrow::MULTIPLIER_SINGLE);
        $this->assertEquals(5, $single->getTotal());

        $double = new Arrow(2, 5, Arrow::MULTIPLIER_DOUBLE);
        $this->assertEquals(10, $double->getTotal());

        $triple = new Arrow(3, 5, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals(15, $triple->getTotal());

        $triple1 = new Arrow(3, 1, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals(3, $triple1->getTotal());

        $triple0 = new Arrow(3, 0, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals(0, $triple0->getTotal());

        $triple20 = new Arrow(3, 20, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals(60, $triple20->getTotal());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testTripleBullsEye()
    {
        new Arrow(1, 25, Arrow::MULTIPLIER_TRIPLE);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWrongNumber1()
    {
        new Arrow(0, 5, Arrow::MULTIPLIER_SINGLE);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWrongNumber2()
    {
        new Arrow(5, 5, Arrow::MULTIPLIER_SINGLE);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidScore()
    {
        new Arrow(1, 21, Arrow::MULTIPLIER_SINGLE);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidMultiplier()
    {
        new Arrow(1, 5, 'quadruple');
    }
}
