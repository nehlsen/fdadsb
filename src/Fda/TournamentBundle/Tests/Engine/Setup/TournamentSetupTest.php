<?php

namespace Fda\TournamentBundle\Tests\Engine\Setup;

use Fda\TournamentBundle\Engine\Setup\RoundSetupSeed;
use Fda\TournamentBundle\Engine\Setup\TournamentSetup;

class TournamentSetupTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGetSeed()
    {
        $seed = RoundSetupSeed::create([[1,2]]);

        $setup = new TournamentSetup();
        $setup->setSeed($seed);
        $this->assertEquals($seed, $setup->getSeed());
    }
}
