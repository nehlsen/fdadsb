<?php

namespace Fda\TournamentBundle\Tests\Engine\Bolts;

use Fda\TournamentBundle\Engine\Bolts\Arrow;

class ArrowTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $single = Arrow::create(5, Arrow::MULTIPLIER_SINGLE);
        $this->assertEquals('5', $single->__toString());

        $double = Arrow::create(5, Arrow::MULTIPLIER_DOUBLE);
        $this->assertEquals('D5', $double->__toString());

        $triple = Arrow::create(5, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals('T5', $triple->__toString());
    }

    public function testIser()
    {
        $single = Arrow::create(5, Arrow::MULTIPLIER_SINGLE);
        $this->assertTrue($single->isSingle());
        $this->assertFalse($single->isDouble());
        $this->assertFalse($single->isTriple());

        $double = Arrow::create(5, Arrow::MULTIPLIER_DOUBLE);
        $this->assertFalse($double->isSingle());
        $this->assertTrue($double->isDouble());
        $this->assertFalse($double->isTriple());

        $triple = Arrow::create(5, Arrow::MULTIPLIER_TRIPLE);
        $this->assertFalse($triple->isSingle());
        $this->assertFalse($triple->isDouble());
        $this->assertTrue($triple->isTriple());
    }

    public function testTotals()
    {
        $single = Arrow::create(5, Arrow::MULTIPLIER_SINGLE);
        $this->assertEquals(5, $single->getTotal());

        $double = Arrow::create(5, Arrow::MULTIPLIER_DOUBLE);
        $this->assertEquals(10, $double->getTotal());

        $triple = Arrow::create(5, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals(15, $triple->getTotal());

        $triple1 = Arrow::create(1, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals(3, $triple1->getTotal());

        $triple0 = Arrow::create(0, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals(0, $triple0->getTotal());

        $triple20 = Arrow::create(20, Arrow::MULTIPLIER_TRIPLE);
        $this->assertEquals(60, $triple20->getTotal());
    }

    public function testSetValidNumber()
    {
        $single = Arrow::create(5, Arrow::MULTIPLIER_SINGLE);
        $single->setNumber(1);
        $this->assertEquals(1, $single->getNumber());

        $double = Arrow::create(5, Arrow::MULTIPLIER_DOUBLE);
        $double->setNumber(2);
        $this->assertEquals(2, $double->getNumber());

        $triple = Arrow::create(5, Arrow::MULTIPLIER_TRIPLE);
        $triple->setNumber(3);
        $this->assertEquals(3, $triple->getNumber());
    }

    /**
     * @expectedException \Fda\TournamentBundle\Engine\Bolts\InvalidArrowException
     */
    public function testTripleBullsEye()
    {
        Arrow::create(25, Arrow::MULTIPLIER_TRIPLE);
    }

    /**
     * @expectedException \Fda\TournamentBundle\Engine\Bolts\InvalidArrowException
     */
    public function testWrongNumber1()
    {
        $arrow = Arrow::create(5, Arrow::MULTIPLIER_SINGLE);
        $arrow->setNumber(0);
    }

    /**
     * @expectedException \Fda\TournamentBundle\Engine\Bolts\InvalidArrowException
     */
    public function testWrongNumber2()
    {
        $arrow = Arrow::create(5, Arrow::MULTIPLIER_SINGLE);
        $arrow->setNumber(5);
    }

    /**
     * @expectedException \Fda\TournamentBundle\Engine\Bolts\InvalidArrowException
     */
    public function testInvalidScore()
    {
        Arrow::create(21, Arrow::MULTIPLIER_SINGLE);
    }

    /**
     * @expectedException \Fda\TournamentBundle\Engine\Bolts\InvalidArrowException
     */
    public function testInvalidMultiplier()
    {
        Arrow::create(5, 'quadruple');
    }
}
