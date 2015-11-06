<?php

namespace Fda\PlayerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Fda\PlayerBundle\Entity\Player;

class LoadSamplePlayers extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $hulk = new Player();
        $hulk->setName('Hulk');
        $hulk->setImageName('hulk.gif');
        $this->setReference('player-0', $hulk);
        $manager->persist($hulk);

        $spiderman = new Player();
        $spiderman->setName('Spiderman');
        $spiderman->setImageName('spiderman.png');
        $this->setReference('player-1', $spiderman);
        $manager->persist($spiderman);

        $superman = new Player();
        $superman->setName('Superman');
        $superman->setImageName('superman.png');
        $this->setReference('player-2', $superman);
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
