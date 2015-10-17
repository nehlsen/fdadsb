<?php

namespace Fda\PlayerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fda\PlayerBundle\Entity\Player;

class LoadSampleUsers implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $hulk = new Player();
        $hulk->setName('Hulk');
        $hulk->setImageName('hulk.gif');
        $manager->persist($hulk);

        $spiderman = new Player();
        $spiderman->setName('Spiderman');
        $spiderman->setImageName('spiderman.png');
        $manager->persist($spiderman);

        $superman = new Player();
        $superman->setName('Superman');
        $superman->setImageName('superman.png');
        $manager->persist($superman);

        $playerNames = [
            'Iron Man',
            'Dr. Octopus',
            'Batman',
            'Wolverine',
            'Adonis',
            'Captain America',
            'Storm',
            'Dracula',
            'Spock',
            'Captain Kirk',
            'Flash',
            'Robin Hood',
            'Uhura',
        ];

        foreach ($playerNames as $playerName) {
            $player = new Player();
            $player->setName($playerName);
            $manager->persist($player);
        }

        $manager->flush();
    }
}
