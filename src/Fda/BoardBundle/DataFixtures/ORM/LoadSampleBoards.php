<?php

namespace Fda\BoardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fda\BoardBundle\Entity\Board;

class LoadSampleBoards implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $blueStage = new Board();
        $blueStage->setName('Blue Stage');
        $blueStage->setImageName('blue.jpeg');
        $manager->persist($blueStage);

        $redStage = new Board();
        $redStage->setName('Red Stage');
        $redStage->setImageName('red.jpeg');
        $manager->persist($redStage);

        $manager->flush();
    }
}
