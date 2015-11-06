<?php

namespace Fda\TournamentBundle\Engine\Factory;

use Doctrine\ORM\EntityManager;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\GameMode;
use Fda\TournamentBundle\Engine\Gears\GameGearsInterface;
use Fda\TournamentBundle\Engine\Gears\GameGearsSimple;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Group;

class GameGearsFactory
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var LegGearsFactory */
    protected $legGearsFactory;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param LegGearsFactory $legGearsFactory
     */
    public function setLegGearsFactory(LegGearsFactory $legGearsFactory)
    {
        $this->legGearsFactory = $legGearsFactory;
    }

    /**
     * @param Group  $group
     * @param Player $player1
     * @param Player $player2
     *
     * @return GameGearsInterface
     */
    public function create(Group $group, Player $player1, Player $player2)
    {
        $roundSetup = $group->getRound()->getSetup();
        $gameMode = $roundSetup->getGameMode();

        $game = new Game($group, $player1, $player2);

        if ($gameMode->getMode() == GameMode::FIRST_TO || $gameMode->getMode() == GameMode::AHEAD) {
            $gears = new GameGearsSimple($game);
        } else {
            throw new \InvalidArgumentException('can not create game-gears for '.$gameMode->getMode());
        }

        return $gears;
    }
}
