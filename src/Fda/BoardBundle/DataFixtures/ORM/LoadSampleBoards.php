<?php

namespace Fda\BoardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fda\BoardBundle\Entity\Board;

class LoadSampleBoards extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $blueStage = new Board();
        $blueStage->setName('Blue Stage');
        $blueStage->setImageName('blue.jpeg');
        $this->setReference('board-0', $blueStage);
        $manager->persist($blueStage);

        $redStage = new Board();
        $redStage->setName('Red Stage');
        $redStage->setImageName('red.jpeg');
        $this->setReference('board-1', $redStage);
        $manager->persist($redStage);

        $manager->flush();
    }
}
