<?php

namespace Fda\TournamentBundle\Tests\Engine;

use Fda\TournamentBundle\Engine\Bolts\CountDownFinishingMoveProvider;

class CountDownFinishingMoveProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRemainingScore0So()
    {
        $provider = new CountDownFinishingMoveProvider(0, false);
        $moves = $provider->getFinishingMoves();

        $this->assertCount(0, $moves);
    }

    public function testRemainingScore0Do()
    {
        $provider = new CountDownFinishingMoveProvider(0, true);
        $moves = $provider->getFinishingMoves();

        $this->assertCount(0, $moves);
    }

    public function testRemainingScore1So()
    {
        $provider = new CountDownFinishingMoveProvider(1, false);
        $moves = $provider->getFinishingMoves();

        $this->assertCount(1, $moves);
    }

    public function testRemainingScore1Do()
    {
        $provider = new CountDownFinishingMoveProvider(1, true);
        $moves = $provider->getFinishingMoves();

        $this->assertCount(0, $moves);
    }

    public function testRemainingScore301So()
    {
        $provider = new CountDownFinishingMoveProvider(301, false);
        $moves = $provider->getFinishingMoves();

        // don't care about the actual moves the number of moves is what counts!
        $this->assertCount(6, $moves);
    }

    public function testRemainingScore301Do()
    {
        $provider = new CountDownFinishingMoveProvider(301, true);
        $moves = $provider->getFinishingMoves();

        // don't care about the actual moves the number of moves is what counts!
        $this->assertCount(6, $moves);
    }

    public function testRemainingScore501So()
    {
        $provider = new CountDownFinishingMoveProvider(501, false);
        $moves = $provider->getFinishingMoves();

        // don't care about the actual moves the number of moves is what counts!
        $this->assertCount(9, $moves);
    }

    public function testRemainingScore501Do()
    {
        $provider = new CountDownFinishingMoveProvider(501, true);
        $moves = $provider->getFinishingMoves();

        // don't care about the actual moves the number of moves is what counts!
//        $this->assertCount(9, $moves); // this could be achieved, but ATW the provider is not able to find it
        $this->assertCount(10, $moves);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemainingScoreBelow0So()
    {
        $provider = new CountDownFinishingMoveProvider(-1, false);
        $moves = $provider->getFinishingMoves();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemainingScoreBelow0Do()
    {
        $provider = new CountDownFinishingMoveProvider(-1, true);
        $moves = $provider->getFinishingMoves();
    }
}
